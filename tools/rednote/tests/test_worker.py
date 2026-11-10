import tempfile
import unittest
from pathlib import Path

from worker import PublicationError, validate_job, connect_db, begin_job, update_job


class RednoteWorkerTest(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.base = Path(self.tmp.name)
        self.inbox = self.base / "inbox"
        self.inbox.mkdir()
        self.image = self.inbox / "image.png"
        self.image.write_bytes(b"test image bytes")
        self.job = {
            "job_id": "vvp:wp:98765:zh-hans:rednote:v1",
            "wp_post_id": 98765,
            "language": "zh-hans",
            "title": "中俄合作",
            "body": "商务信息",
            "media_type": "images",
            "media_paths": [str(self.image)],
            "tags": ["中俄合作"],
        }

    def tearDown(self):
        self.tmp.cleanup()

    def test_validates_zh_post(self):
        self.assertEqual(validate_job(self.job, self.inbox)["media_paths"], [str(self.image)])

    def test_rejects_non_chinese_wpml(self):
        self.job["language"] = "ru"
        with self.assertRaises(PublicationError):
            validate_job(self.job, self.inbox)

    def test_rejects_outside_inbox(self):
        outside = self.base / "outside.png"
        outside.write_bytes(b"x")
        self.job["media_paths"] = [str(outside)]
        with self.assertRaises(PublicationError):
            validate_job(self.job, self.inbox)

    def test_idempotency_blocks_uncertain_submission(self):
        job = validate_job(self.job, self.inbox)
        db = connect_db(self.base / "jobs.sqlite3")
        self.assertEqual(begin_job(db, job, inspect=False), "RUNNING")
        update_job(db, job["job_id"], "UNCERTAIN", "network result unknown")
        self.assertEqual(begin_job(db, job, inspect=False), "SKIPPED_UNCERTAIN")
        db.close()

    def test_retries_only_before_publish(self):
        job = validate_job(self.job, self.inbox)
        db = connect_db(self.base / "jobs.sqlite3")
        self.assertEqual(begin_job(db, job, inspect=False), "RUNNING")
        update_job(db, job["job_id"], "FAILED_BEFORE_SUBMIT", "UI changed")
        self.assertEqual(begin_job(db, job, inspect=False), "RUNNING")
        db.close()


if __name__ == "__main__":
    unittest.main()
