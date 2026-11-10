#!/usr/bin/env python3
"""Experimental REDnote publisher: one job per invocation, fail-closed.

Uses the user's legitimate stored creator session and Playwright UI. No private
API, CAPTCHA bypass, proxy spoofing, or credential collection. The selectors
need verification against the current real creator UI before production use.
"""
from __future__ import annotations

import argparse
import asyncio
import json
import os
from pathlib import Path
import re
import sqlite3
from datetime import datetime, timezone

CREATOR_UPLOAD_URL = "https://creator.xiaohongshu.com/creator-micro/content/upload"
MAX_TITLE_CHARS = 20
STATE_VERSION = 1


class PublicationError(Exception):
    pass


def utc_now() -> str:
    return datetime.now(timezone.utc).isoformat()


def validate_job(raw: dict, inbox: Path) -> dict:
    if not isinstance(raw, dict):
        raise PublicationError("Job must be a JSON object")
    required = ("job_id", "wp_post_id", "language", "title", "body", "media_type", "media_paths")
    for field in required:
        if not raw.get(field):
            raise PublicationError(f"Missing required field: {field}")
    if raw["language"] != "zh-hans":
        raise PublicationError("Only WPML zh-hans content can be published")
    if not isinstance(raw["wp_post_id"], int) or raw["wp_post_id"] <= 0:
        raise PublicationError("wp_post_id must be a positive integer")
    if not re.fullmatch(r"[a-zA-Z0-9:_-]{10,180}", str(raw["job_id"])):
        raise PublicationError("Unsafe job_id")
    if raw["media_type"] not in ("images", "video"):
        raise PublicationError("media_type must be images or video")
    title = raw["title"].strip()
    body = raw["body"].strip()
    if not title or len(title) > MAX_TITLE_CHARS:
        raise PublicationError("Title must be 1–20 characters; adapt title upstream")
    if not body or len(body) > 900:
        raise PublicationError("Body must be 1–900 characters; adapt upstream")
    paths = raw["media_paths"]
    if not isinstance(paths, list) or not paths:
        raise PublicationError("media_paths must be a non-empty list")
    if (raw["media_type"] == "video" and len(paths) != 1) or (raw["media_type"] == "images" and len(paths) > 9):
        raise PublicationError("Only one video or 1–9 images")
    resolved_inbox = inbox.resolve(strict=True)
    validated_paths = []
    for value in paths:
        if not isinstance(value, str):
            raise PublicationError("media_paths must contain strings")
        path = Path(value).resolve(strict=True)
        if not path.is_file() or not path.is_relative_to(resolved_inbox):
            raise PublicationError("Media must be local files under the configured inbox")
        allowed = {".jpg", ".jpeg", ".png", ".webp"} if raw["media_type"] == "images" else {".mp4"}
        if path.suffix.lower() not in allowed:
            raise PublicationError(f"Unsupported media extension: {path.suffix}")
        if path.stat().st_size > (200 * 1024 * 1024 if raw["media_type"] == "video" else 10 * 1024 * 1024):
            raise PublicationError("Media file too large")
        validated_paths.append(str(path))
    tags = raw.get("tags", [])
    if not isinstance(tags, list) or len(tags) > 12 or any(not isinstance(t, str) or len(t) > 24 for t in tags):
        raise PublicationError("tags must be a list of up to 12 short strings")
    return {**raw, "title": title, "body": body, "media_paths": validated_paths, "tags": tags}


def connect_db(path: Path) -> sqlite3.Connection:
    path.parent.mkdir(parents=True, exist_ok=True)
    db = sqlite3.connect(str(path), timeout=30, isolation_level=None)
    db.execute("PRAGMA journal_mode=WAL")
    db.execute("""CREATE TABLE IF NOT EXISTS delivery (
        job_id TEXT PRIMARY KEY,
        wp_post_id INTEGER NOT NULL,
        state TEXT NOT NULL,
        note_url TEXT,
        attempts INTEGER NOT NULL DEFAULT 0,
        error TEXT,
        updated_at TEXT NOT NULL
    )""")
    return db


def begin_job(db: sqlite3.Connection, job: dict, inspect: bool) -> str:
    if inspect:
        return "INSPECT"
    db.execute("BEGIN IMMEDIATE")
    try:
        row = db.execute("SELECT state, attempts FROM delivery WHERE job_id=?", (job["job_id"],)).fetchone()
        if row and row[0] in ("SUBMITTED", "UNCERTAIN", "PUBLISHED", "RUNNING"):
            db.execute("COMMIT")
            return "SKIPPED_" + row[0]
        if row and row[1] >= 3:
            db.execute("COMMIT")
            return "SKIPPED_MAX_ATTEMPTS"
        if row:
            db.execute("UPDATE delivery SET state='RUNNING', attempts=attempts+1, error=NULL, updated_at=? WHERE job_id=?",
                       (utc_now(), job["job_id"]))
        else:
            db.execute("INSERT INTO delivery (job_id, wp_post_id, state, attempts, updated_at) VALUES (?, ?, 'RUNNING', 1, ?)",
                       (job["job_id"], job["wp_post_id"], utc_now()))
        db.execute("COMMIT")
        return "RUNNING"
    except BaseException:
        db.execute("ROLLBACK")
        raise


def update_job(db: sqlite3.Connection, job_id: str, state: str, error: str | None = None, note_url: str | None = None) -> None:
    db.execute("UPDATE delivery SET state=?, error=?, note_url=?, updated_at=? WHERE job_id=?",
               (state, error[:500] if error else None, note_url, utc_now(), job_id))


async def first_visible(locators):
    for locator in locators:
        try:
            if await locator.count() and await locator.first.is_visible():
                return locator.first
        except Exception:
            pass
    return None


async def populate(page, job: dict) -> None:
    await page.goto(CREATOR_UPLOAD_URL, wait_until="domcontentloaded", timeout=45000)
    await page.wait_for_timeout(1600)
    if "login" in page.url.lower() or await page.get_by_text("扫码登录").count():
        raise PublicationError("AUTH_REQUIRED: saved session expired")
    label = re.compile("上传图文|图文笔记") if job["media_type"] == "images" else re.compile("上传视频|视频笔记")
    tab = await first_visible([page.get_by_text(label), page.get_by_role("tab", name=label)])
    if tab:
        await tab.click()
        await page.wait_for_timeout(600)
    inputs = page.locator("input[type=file]")
    if not await inputs.count():
        raise PublicationError("UPLOAD_SELECTOR_CHANGED: input[type=file] missing")
    media = job["media_paths"]
    selected = None
    for idx in range(await inputs.count()):
        item = inputs.nth(idx)
        accept = (await item.get_attribute("accept") or "").lower()
        if job["media_type"] == "images" and ("image" in accept or not accept):
            selected = item
            break
        if job["media_type"] == "video" and ("video" in accept or not accept):
            selected = item
            break
    if selected is None:
        raise PublicationError("UPLOAD_SELECTOR_CHANGED: matching file input missing")
    await selected.set_input_files(media)
    await page.wait_for_timeout(1700)
    title = await first_visible([
        page.locator('input[placeholder*="标题"]'),
        page.locator('input[placeholder*="标题"]'),
        page.locator('input[maxlength="20"]')
    ])
    if title is None:
        raise PublicationError("TITLE_SELECTOR_CHANGED")
    await title.fill(job["title"])
    body = await first_visible([
        page.locator('div[contenteditable="true"]'),
        page.locator('[role="textbox"][contenteditable="true"]'),
        page.locator('textarea[placeholder*="正文"]')
    ])
    if body is None:
        raise PublicationError("BODY_SELECTOR_CHANGED")
    await body.fill(job["body"] + ("\n" + " ".join("#" + t.lstrip("#") for t in job["tags"]) if job["tags"] else ""))
    await page.wait_for_timeout(600)


async def run_browser(job: dict, session: Path, screenshots: Path, inspect: bool, db: sqlite3.Connection) -> str:
    from playwright.async_api import async_playwright

    if not session.is_file():
        raise PublicationError("AUTH_REQUIRED: server-side session state missing")
    screenshots.mkdir(parents=True, exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        try:
            context = await browser.new_context(storage_state=str(session), locale="zh-CN")
            page = await context.new_page()
            await populate(page, job)
            await page.screenshot(path=str(screenshots / (job["job_id"].replace(":", "_") + "_prepared.png")), full_page=True)
            if inspect:
                return "INSPECT_OK"
            publish = await first_visible([
                page.get_by_role("button", name=re.compile("^发布(笔记)?$")),
                page.get_by_text(re.compile("^发布(笔记)?$"))
            ])
            if publish is None:
                raise PublicationError("PUBLISH_SELECTOR_CHANGED")
            # From this point the result is UNKNOWN on network/process crash.
            # Never auto-submit the same job twice.
            update_job(db, job["job_id"], "UNCERTAIN", "Button about to be clicked")
            await publish.click(timeout=20000)
            await page.wait_for_timeout(6000)
            await page.screenshot(path=str(screenshots / (job["job_id"].replace(":", "_") + "_result.png")), full_page=True)
            success = await page.get_by_text(re.compile("发布成功|发布完成|提交成功|审核中")).count()
            if success:
                update_job(db, job["job_id"], "SUBMITTED", "Submission observed; public URL not yet verified")
                return "SUBMITTED"
            # Must be reconciled automatically by checking the creator's notes list
            # before attempting any further submission.
            return "UNCERTAIN"
        finally:
            await browser.close()


async def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--job", type=Path, required=True)
    parser.add_argument("--inbox", type=Path, required=True)
    parser.add_argument("--db", type=Path, required=True)
    parser.add_argument("--session", type=Path, required=True)
    parser.add_argument("--shots", type=Path, required=True)
    parser.add_argument("--inspect", action="store_true", help="Fill and screenshot, never submit")
    args = parser.parse_args()
    job = validate_job(json.loads(args.job.read_text(encoding="utf-8")), args.inbox)
    db = connect_db(args.db)
    status = begin_job(db, job, args.inspect)
    if status.startswith("SKIPPED"):
        print(json.dumps({"job_id": job["job_id"], "status": status}))
        return 0
    try:
        result = await run_browser(job, args.session, args.shots, args.inspect, db)
    except Exception as exc:
        state = db.execute("SELECT state FROM delivery WHERE job_id=?", (job["job_id"],)).fetchone()
        if not args.inspect and state and state[0] == "RUNNING":
            update_job(db, job["job_id"], "FAILED_BEFORE_SUBMIT", str(exc))
        print(json.dumps({"job_id": job["job_id"], "status": "FAILED", "error": str(exc)}, ensure_ascii=False))
        return 2
    finally:
        db.close()
    print(json.dumps({"job_id": job["job_id"], "status": result}, ensure_ascii=False))
    return 0 if result in ("INSPECT_OK", "SUBMITTED") else 3


if __name__ == "__main__":
    raise SystemExit(asyncio.run(main()))
