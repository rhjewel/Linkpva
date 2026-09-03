(function () {
    "use strict"

    const body = document.body;
    const header = document.querySelector("[data-header]");
    const menuToggle = document.querySelector("[data-menu-toggle]");
    const mobileMenu = document.querySelector("[data-mobile-menu]");
    const menuClose = document.querySelector("[data-menu-close]");
    const menuBackdrop = document.querySelector("[data-menu-backdrop]");
    const searchToggle = document.querySelector("[data-search-toggle]");
    const searchForm = document.querySelector("[data-search-form]");
    const backToTop = document.querySelector("[data-back-to-top]");
    const currentYear = document.querySelector("[data-current-year]");
    const parentMenuLinks = document.querySelectorAll(".linkpva-primary-nav .menu-item-has-children > a");

    function closeSubmenus(scope) {
        const container = scope || document;
        const openItems = Array.from(container.querySelectorAll(".menu-item-has-children.is-submenu-open"));

        if (container.matches && container.matches(".menu-item-has-children.is-submenu-open")) {
            openItems.unshift(container);
        }

        openItems.forEach(function (item) {
            item.classList.remove("is-submenu-open");
            item.querySelector(":scope > a") ?.setAttribute("aria-expanded", "false");
        });
    }

    function setMenuState(isOpen) {
        if (!menuToggle || !mobileMenu) return;

        menuToggle.setAttribute("aria-expanded", String(isOpen));
        menuToggle.querySelector(".visually-hidden").textContent = isOpen ? "Close navigation" : "Open navigation";
        mobileMenu.classList.toggle("is-open", isOpen);
        menuBackdrop ?.classList.toggle("is-visible", isOpen);
        menuBackdrop ?.setAttribute("aria-hidden", String(!isOpen));
        body.classList.toggle("is-menu-open", isOpen);

        if (!isOpen) closeSubmenus(mobileMenu);
    }

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener("click", function () {
            setMenuState(menuToggle.getAttribute("aria-expanded") !== "true");
        });

        mobileMenu.addEventListener("click", function (event) {
            const link = event.target.closest("a");

            if (link && !link.parentElement.classList.contains("menu-item-has-children")) {
                setMenuState(false);
            }
        });

        menuClose ?.addEventListener("click", function () {
            setMenuState(false);
            menuToggle.focus();
        });

        menuBackdrop ?.addEventListener("click", function () {
            setMenuState(false);
            menuToggle.focus();
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && menuToggle.getAttribute("aria-expanded") === "true") {
                setMenuState(false);
                menuToggle.focus();
            }
        });

        window.addEventListener("resize", function () {
            if (window.innerWidth >= 992) setMenuState(false);
        });
    }

    if (searchToggle && searchForm) {
        searchToggle.addEventListener("click", function () {
            const willOpen = searchForm.hidden;
            searchForm.hidden = !willOpen;
            searchToggle.setAttribute("aria-expanded", String(willOpen));

            if (willOpen) {
                searchForm.querySelector("input").focus();
            }
        });
    }

    parentMenuLinks.forEach(function (link) {
        link.addEventListener("click", function (event) {
            if (window.innerWidth >= 992) return;

            event.preventDefault();
            const item = link.parentElement;
            const willOpen = link.getAttribute("aria-expanded") !== "true";
            const parentList = item.parentElement;

            parentList.querySelectorAll(":scope > .menu-item-has-children.is-submenu-open").forEach(function (sibling) {
                if (sibling !== item) closeSubmenus(sibling);
            });

            item.classList.toggle("is-submenu-open", willOpen);
            link.setAttribute("aria-expanded", String(willOpen));
        });
    });

    document.querySelectorAll("[data-quantity-minus], [data-quantity-plus]").forEach(function (button) {
        button.addEventListener("click", function () {
            const quantity = button.closest(".linkpva-quantity");
            const input = quantity.querySelector("[data-quantity-input]");
            const minimum = Number(input.min) || 1;
            const maximum = Number(input.max) || 999;
            const direction = button.hasAttribute("data-quantity-plus") ? 1 : -1;
            input.value = Math.min(maximum, Math.max(minimum, Number(input.value || minimum) + direction));
            input.dispatchEvent(new Event("change", {
                bubbles: true
            }));
        });
    });

    document.querySelectorAll("[data-tabs]").forEach(function (tabs) {
        const buttons = tabs.querySelectorAll("[data-tab-target]");

        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                buttons.forEach(function (currentButton) {
                    const panel = document.getElementById(currentButton.dataset.tabTarget);
                    const isActive = currentButton === button;
                    currentButton.classList.toggle("is-active", isActive);
                    currentButton.setAttribute("aria-selected", String(isActive));
                    panel.hidden = !isActive;
                });
            });
        });
    });

    document.querySelectorAll("[data-blog-listing]").forEach(function (listing) {
        const filterButtons = listing.querySelectorAll("[data-blog-filter]");
        const blogItems = listing.querySelectorAll("[data-blog-item]");
        const searchForm = listing.querySelector("[data-blog-search-form]");
        const searchInput = listing.querySelector("[data-blog-search]");
        let activeCategory = "all";

        function filterArticles() {
            const query = searchInput ? searchInput.value.trim().toLowerCase() : "";

            blogItems.forEach(function (item) {
                const matchesCategory = activeCategory === "all" || item.dataset.category === activeCategory;
                const matchesSearch = !query || item.textContent.toLowerCase().includes(query);
                item.hidden = !(matchesCategory && matchesSearch);
            });
        }

        filterButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                activeCategory = button.dataset.blogFilter;

                filterButtons.forEach(function (currentButton) {
                    const isActive = currentButton === button;
                    currentButton.classList.toggle("is-active", isActive);
                    currentButton.setAttribute("aria-pressed", String(isActive));
                });

                filterArticles();
            });
        });

        searchForm ?.addEventListener("submit", function (event) {
            event.preventDefault();
            filterArticles();
        });

        searchInput ?.addEventListener("input", filterArticles);
    });

    document.querySelectorAll("[data-demo-form]").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            const status = form.querySelector("[data-form-status]");

            if (status) {
                status.classList.add("is-visible");
                status.focus({
                    preventScroll: true
                });
            }
        });
    });

    document.querySelectorAll(".linkpva-product-thumbnails button").forEach(function (button) {
        button.addEventListener("click", function () {
            button.parentElement.querySelectorAll("button").forEach(function (currentButton) {
                currentButton.classList.toggle("is-active", currentButton === button);
            });
        });
    });

    if (typeof Swiper !== "undefined") {
        document.querySelectorAll(".linkpva-article-hero.is-gallery .blog-archive-slider").forEach(function (slider) {
            const gallery = slider.closest(".linkpva-article-hero");
            const slides = slider.querySelectorAll(".swiper-slide");

            new Swiper(slider, {
                loop: slides.length > 1,
                slidesPerView: 1,
                navigation: {
                    nextEl: gallery.querySelector(".blog1-next"),
                    prevEl: gallery.querySelector(".blog1-prev")
                }
            });
        });
    }

    document.querySelectorAll("[data-accordion] button").forEach(function (button) {
        button.addEventListener("click", function () {
            const accordion = button.closest("[data-accordion]");
            const item = button.closest(".linkpva-accordion-item");
            const panel = document.getElementById(button.getAttribute("aria-controls"));
            const willOpen = button.getAttribute("aria-expanded") !== "true";

            accordion.querySelectorAll(".linkpva-accordion-item").forEach(function (currentItem) {
                const currentButton = currentItem.querySelector("button");
                const currentPanel = document.getElementById(currentButton.getAttribute("aria-controls"));
                currentButton.setAttribute("aria-expanded", "false");
                currentPanel.hidden = true;
                currentItem.classList.remove("is-open");
            });

            if (willOpen) {
                button.setAttribute("aria-expanded", "true");
                panel.hidden = false;
                item.classList.add("is-open");
            }
        });
    });

    function updateScrollState() {
        if (header) header.classList.toggle("is-sticky", window.scrollY > 40);
        if (backToTop) backToTop.classList.toggle("is-visible", window.scrollY > 500);
    }

    window.addEventListener("scroll", updateScrollState, {
        passive: true
    });
    updateScrollState();

    if (backToTop) {
        backToTop.addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeSubmenus();
            setMenuState(false);

            if (searchForm && !searchForm.hidden) {
                searchForm.hidden = true;
                searchToggle.setAttribute("aria-expanded", "false");
                searchToggle.focus();
            }
        }
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth >= 992) setMenuState(false);
    });

    if (currentYear) currentYear.textContent = new Date().getFullYear();

}());



