(function () {
    'use strict';

    const DESKTOP_MIN = 769;
    const GAP = 16;
    const ROOT_SELECTOR = '.vvp-priority-menu .thegem-te-menu > nav.thegem-te-menu__default';

    function debounce(fn, delay) {
        let timer = null;
        return function () {
            clearTimeout(timer);
            const args = arguments;
            timer = setTimeout(function () {
                fn.apply(null, args);
            }, delay);
        };
    }

    function isVisible(el) {
        if (!el) return false;
        const style = window.getComputedStyle(el);
        if (style.display === 'none' || style.visibility === 'hidden') return false;
        const rect = el.getBoundingClientRect();
        return rect.width > 0 && rect.height > 0;
    }

    function outerWidth(el) {
        if (!el || !isVisible(el)) return 0;
        const rect = el.getBoundingClientRect();
        const style = window.getComputedStyle(el);
        const ml = parseFloat(style.marginLeft) || 0;
        const mr = parseFloat(style.marginRight) || 0;
        return Math.ceil(rect.width + ml + mr);
    }

    function copyAnchorAttrs(from, to) {
        ['href', 'target', 'rel', 'title'].forEach(function (attr) {
            const val = from.getAttribute(attr);
            if (val !== null) to.setAttribute(attr, val);
        });
        to.innerHTML = from.innerHTML;
    }

    function initPriorityMenu(nav) {
        if (!nav || nav.dataset.vvpPriorityInit === '1') return;

        const widget = nav.closest('.vvp-priority-menu');
        const menuList = nav.querySelector('ul.nav-menu');
        if (!widget || !menuList) return;

        const moreItem = document.createElement('li');
        moreItem.className = 'menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children vvp-more-item';
        moreItem.innerHTML = `
            <a href="#" class="vvp-more-link" aria-haspopup="true" aria-expanded="false">Еще</a>
            <ul class="sub-menu styled vvp-more-submenu"></ul>
        `;

        const moreLink = moreItem.querySelector('.vvp-more-link');
        const moreSubmenu = moreItem.querySelector('.vvp-more-submenu');

        let ignoreNextClick = false;

        if (!menuList.contains(moreItem)) {
            menuList.appendChild(moreItem);
        }

        function isDesktopMode() {
            return (
                window.innerWidth >= DESKTOP_MIN &&
                nav.classList.contains('desktop-view') &&
                !nav.classList.contains('mobile-view') &&
                isVisible(widget) &&
                isVisible(nav)
            );
        }

        function getTopItems() {
            return Array.from(menuList.children).filter(function (li) {
                return (
                    li.classList.contains('menu-item') &&
                    !li.classList.contains('menu-item-widget') &&
                    !li.classList.contains('vvp-more-item')
                );
            });
        }

        function getVisibleTopItems() {
            return getTopItems().filter(function (li) {
                return !li.classList.contains('vvp-hidden-original');
            });
        }

        function availableWidth() {
            const candidates = [
                widget.getBoundingClientRect().width,
                nav.getBoundingClientRect().width,
                menuList.parentElement ? menuList.parentElement.getBoundingClientRect().width : 0
            ].filter(function (v) { return v > 0; });

            if (!candidates.length) return 0;
            return Math.floor(Math.min.apply(null, candidates));
        }

        function measureMoreWidth() {
            const prevDisplay = moreItem.style.display;
            const prevVisibility = moreItem.style.visibility;
            const prevPosition = moreItem.style.position;
            const prevPointerEvents = moreItem.style.pointerEvents;

            moreItem.style.display = 'list-item';
            moreItem.style.visibility = 'hidden';
            moreItem.style.position = 'absolute';
            moreItem.style.pointerEvents = 'none';

            const width = outerWidth(moreItem);

            moreItem.style.display = prevDisplay;
            moreItem.style.visibility = prevVisibility;
            moreItem.style.position = prevPosition;
            moreItem.style.pointerEvents = prevPointerEvents;

            return width;
        }

        function visibleItemsWidth() {
            return getVisibleTopItems().reduce(function (sum, li) {
                return sum + outerWidth(li);
            }, 0);
        }

        function openMore() {
            if (moreItem.style.display === 'none') return;
            moreItem.classList.add('vvp-open');
            moreLink.setAttribute('aria-expanded', 'true');
        }

        function closeMore() {
            moreItem.classList.remove('vvp-open');
            moreLink.setAttribute('aria-expanded', 'false');
        }

        function buildClone(li) {
            const link = li.querySelector(':scope > a');
            if (!link) return null;

            const clone = document.createElement('li');
            clone.className = 'menu-item vvp-more-clone-item';

            const cloneLink = document.createElement('a');
            copyAnchorAttrs(link, cloneLink);
            clone.appendChild(cloneLink);

            return clone;
        }

        function resetMenu() {
            closeMore();
            moreSubmenu.innerHTML = '';
            moreItem.style.display = 'none';

            getTopItems().forEach(function (li) {
                li.classList.remove('vvp-hidden-original');
            });
        }

        function updateMenu() {
            resetMenu();

            if (!isDesktopMode()) return;

            const items = getTopItems();
            if (!items.length) return;

            const free = availableWidth();
            if (!free) return;

            let total = visibleItemsWidth();

            if (total <= free - GAP) {
                return;
            }

            const moreWidth = measureMoreWidth();
            moreItem.style.display = 'list-item';

            while (getVisibleTopItems().length && (visibleItemsWidth() + moreWidth > free - GAP)) {
                const last = getVisibleTopItems().pop();
                if (!last) break;

                last.classList.add('vvp-hidden-original');

                const clone = buildClone(last);
                if (clone) {
                    moreSubmenu.insertBefore(clone, moreSubmenu.firstChild);
                }
            }

            if (!moreSubmenu.children.length) {
                moreItem.style.display = 'none';
            }
        }

        function handlePointerDown(e) {
            if (!isDesktopMode()) return;
            if (e.pointerType !== 'touch' && e.pointerType !== 'pen') return;

            e.preventDefault();
            e.stopPropagation();

            if (moreItem.classList.contains('vvp-open')) {
                closeMore();
            } else {
                openMore();
            }

            moreLink.blur();

            ignoreNextClick = true;
            setTimeout(function () {
                ignoreNextClick = false;
            }, 350);
        }

        if (window.PointerEvent) {
            moreLink.addEventListener('pointerdown', handlePointerDown);
        }

        moreItem.addEventListener('mouseenter', function () {
            if (isDesktopMode()) openMore();
        });

        moreItem.addEventListener('mouseleave', function () {
            closeMore();
        });

        moreLink.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (!isDesktopMode()) return;
            if (ignoreNextClick) return;

            if (moreItem.classList.contains('vvp-open')) {
                closeMore();
            } else {
                openMore();
            }
        });

        document.addEventListener('click', function (e) {
            if (!moreItem.contains(e.target)) {
                closeMore();
            }
        });

        const debouncedUpdate = debounce(updateMenu, 80);

        updateMenu();

        window.addEventListener('resize', debouncedUpdate);
        window.addEventListener('orientationchange', debouncedUpdate);

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function () {
                updateMenu();
            });
        }

        if (typeof ResizeObserver !== 'undefined') {
            const ro = new ResizeObserver(debouncedUpdate);
            ro.observe(widget);
            ro.observe(nav);
            ro.observe(menuList);
        }

        window.addEventListener('load', function () {
            setTimeout(updateMenu, 100);
            setTimeout(updateMenu, 300);
            setTimeout(updateMenu, 700);
        });

        nav.dataset.vvpPriorityInit = '1';
    }

    function initAllPriorityMenus() {
        document.querySelectorAll(ROOT_SELECTOR).forEach(function (nav) {
            initPriorityMenu(nav);
        });
    }

    document.addEventListener('DOMContentLoaded', initAllPriorityMenus);
    window.addEventListener('load', initAllPriorityMenus);
})();