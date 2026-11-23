document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form[data-confirm]").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    var menuToggle = document.querySelector(".landing-menu-toggle");
    var landingNav = document.querySelector(".landing-nav");
    if (menuToggle && landingNav) {
        menuToggle.addEventListener("click", function () {
            var isOpen = menuToggle.getAttribute("aria-expanded") === "true";
            menuToggle.setAttribute("aria-expanded", String(!isOpen));
            landingNav.classList.toggle("is-open", !isOpen);
        });
        landingNav.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                menuToggle.setAttribute("aria-expanded", "false");
                landingNav.classList.remove("is-open");
            });
        });
    }

    var previewTabs = document.querySelectorAll(".preview-tab");
    var previewStages = {
        applied: {
            company: "Northstar Labs",
            role: "Product Design Intern",
            mark: "n",
            status: "Applied",
            progress: "35%",
            next: "Next up: follow up next week",
            count: "1 of 4"
        },
        interview: {
            company: "Daybreak Studio",
            role: "UX Research Intern",
            mark: "d",
            status: "Interview",
            progress: "68%",
            next: "Next up: prep your questions",
            count: "2 of 4"
        },
        offer: {
            company: "Mosaic Works",
            role: "Creative Intern",
            mark: "m",
            status: "Offer received",
            progress: "100%",
            next: "Next up: celebrate this moment",
            count: "4 of 4"
        }
    };
    if (previewTabs.length) {
        previewTabs.forEach(function (tab) {
            tab.addEventListener("click", function () {
                var stage = previewStages[tab.dataset.stage];
                if (!stage) return;
                previewTabs.forEach(function (item) {
                    var selected = item === tab;
                    item.classList.toggle("is-active", selected);
                    item.setAttribute("aria-selected", String(selected));
                });
                document.querySelector("[data-preview-company]").textContent = stage.company;
                document.querySelector("[data-preview-role]").textContent = stage.role;
                document.querySelector("[data-preview-mark]").textContent = stage.mark;
                var status = document.querySelector("[data-preview-status]");
                status.textContent = stage.status;
                status.className = "preview-status status-" + tab.dataset.stage;
                document.querySelector("[data-preview-progress]").style.width = stage.progress;
                document.querySelector("[data-preview-next]").textContent = stage.next;
                document.querySelector("[data-preview-count]").textContent = stage.count;
            });
        });
    }

    var searchInput = document.querySelector("[data-live-search]");
    var searchRows = document.querySelectorAll("[data-searchable]");
    if (searchInput && searchRows.length) {
        var emptySearchRow = document.querySelector("[data-search-empty]");
        var liveCount = document.querySelector("[data-live-count]");
        searchInput.addEventListener("input", function () {
            var query = searchInput.value.trim().toLowerCase();
            var visibleCount = 0;
            searchRows.forEach(function (row) {
                var matches = row.textContent.toLowerCase().indexOf(query) !== -1;
                row.hidden = !matches;
                if (matches) visibleCount += 1;
            });
            if (emptySearchRow) emptySearchRow.hidden = visibleCount > 0;
            if (liveCount) {
                liveCount.textContent = visibleCount + (visibleCount === 1 ? " application" : " applications");
            }
        });
    }

    var internshipForm = document.querySelector("[data-internship-form]");
    if (internshipForm) {
        var previewCompany = document.querySelector('[data-form-preview="company"]');
        var previewRole = document.querySelector('[data-form-preview="role"]');
        var previewLocation = document.querySelector('[data-form-preview="location"]');
        var previewInitial = document.querySelector('[data-form-preview="initial"]');
        var previewStatus = document.querySelector('[data-form-preview="status"]');
        internshipForm.querySelectorAll("[data-preview]").forEach(function (field) {
            field.addEventListener("input", updateInternshipPreview);
            field.addEventListener("change", updateInternshipPreview);
        });
        function updateInternshipPreview() {
            var company = internshipForm.elements.company.value.trim();
            var role = internshipForm.elements.role.value.trim();
            var location = internshipForm.elements.location.value.trim();
            var status = internshipForm.elements.status.value;
            previewCompany.textContent = company || "Your company";
            previewRole.textContent = role || "Role title";
            previewLocation.textContent = location || "Location not added";
            previewInitial.textContent = company ? company.charAt(0).toUpperCase() : "?";
            previewStatus.textContent = status;
            previewStatus.className = "status " + status.toLowerCase().replace(/ /g, "-");
        }
    }
});
