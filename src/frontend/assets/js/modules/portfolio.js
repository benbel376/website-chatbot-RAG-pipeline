'use strict';

export function initializePortfolio(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Get references to all required elements
    const projectItems = container.querySelectorAll(".project-post-item");
    const projectListSection = container.querySelector("[data-content-list]");
    const projectDetailsSection = container.querySelector("[data-content-details]");
    const goBackBtn = container.querySelector("[data-go-back]");
    const detailUnits = container.querySelectorAll(".details-unit");
    const sortButtons = container.querySelectorAll(".filter-sort-btn");
    const selectButton = container.querySelector('[data-select]');
    const selectValue = container.querySelector('[data-select-value]');
    const selectList = container.querySelector('.select-list');
    const selectItems = container.querySelectorAll('[data-select-item]');
    const searchInput = container.querySelector('[data-search-input]');

    let selectedTags = [];

    // Initialize project items click handlers
    projectItems.forEach((item) => {
        item.addEventListener("click", handleProjectClick);
    });

    function handleProjectClick(event) {
        event.preventDefault();
        const contentId = this.dataset.contentId;
        showProjectDetails(contentId);
    }

    function showProjectDetails(contentId) {
        detailUnits.forEach((unit) => {
            unit.hidden = true;
            unit.classList.remove("active");
        });

        const targetDetail = container.querySelector(`#${contentId}-details`);
        if (targetDetail) {
            targetDetail.hidden = false;
            targetDetail.classList.add("active");

            projectListSection.classList.add("hidden");
            projectDetailsSection.classList.add("active");

            goBackBtn.hidden = false;
            goBackBtn.classList.add("visible");
        }
    }

    // Handle go back button click
    goBackBtn.addEventListener("click", function() {
        projectDetailsSection.classList.remove("active");
        projectListSection.classList.remove("hidden");

        detailUnits.forEach((unit) => {
            unit.hidden = true;
            unit.classList.remove("active");
        });

        goBackBtn.classList.remove("visible");
        setTimeout(() => {
            goBackBtn.hidden = true;
        }, 250);
    });

    // Sorting functionality
    function initializeSorting() {
        sortButtons.forEach((button) => {
            button.addEventListener("click", function() {
                const sortType = this.getAttribute("data-sort");
                let sortOrder = this.getAttribute("data-order");

                sortOrder = sortOrder === "asc" ? "desc" : "asc";
                this.setAttribute("data-order", sortOrder);

                sortButtons.forEach((btn) => {
                    btn.classList.add("inactive");
                    btn.classList.remove("active");
                    btn.querySelector("ion-icon").setAttribute("name", "arrow-down-outline");
                });

                this.classList.remove("inactive");
                this.classList.add("active");
                this.querySelector("ion-icon").setAttribute("name",
                    sortOrder === "asc" ? "arrow-up-outline" : "arrow-down-outline");

                if (sortType === "date") {
                    sortByDate(sortOrder);
                } else if (sortType === "title") {
                    sortByTitle(sortOrder);
                }
            });
        });
    }

    function sortByDate(order) {
        const sortedItems = Array.from(projectItems).sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return order === "asc" ? dateA - dateB : dateB - dateA;
        });
        renderSortedItems(sortedItems);
    }

    function sortByTitle(order) {
        const sortedItems = Array.from(projectItems).sort((a, b) => {
            const titleA = a.dataset.title ? a.dataset.title.toLowerCase() : '';
            const titleB = b.dataset.title ? b.dataset.title.toLowerCase() : '';
            return order === "asc" ? titleA.localeCompare(titleB) : titleB.localeCompare(titleA);
        });
        renderSortedItems(sortedItems);
    }

    function renderSortedItems(sortedItems) {
        const contentList = container.querySelector(".content-list-items");
        contentList.innerHTML = "";
        sortedItems.forEach((item) => {
            contentList.appendChild(item);
        });
    }

    // Tag filtering functionality
    function initializeTagFiltering() {
        if (selectButton) {
            selectButton.addEventListener("click", function() {
                this.classList.toggle('active');
                if (selectList) {
                    selectList.classList.toggle('active');
                }
            });
        }

        if (selectItems) {
            selectItems.forEach((item) => {
                item.addEventListener('change', function() {
                    const tag = this.value;
                    if (this.checked) {
                        selectedTags.push(tag);
                    } else {
                        selectedTags = selectedTags.filter(t => t !== tag);
                    }
                    updateSelectValue();
                    filterProjects();
                });
            });
        }
    }

    function updateSelectValue() {
        if (selectValue) {
            selectValue.textContent = selectedTags.length > 0 ? "Filtered" : "Filter by tags";
        }
    }

    // Search functionality
    function initializeSearch() {
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterProjects, 300);
            });
        }
    }

    function filterProjects() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

        projectItems.forEach((project) => {
            const projectTags = project.dataset.tags ? project.dataset.tags.toLowerCase().split(",") : [];
            const projectTitle = project.dataset.title ? project.dataset.title.toLowerCase() : '';

            const matchesTags = selectedTags.length === 0 ||
                selectedTags.every(tag => projectTags.includes(tag.toLowerCase()));
            const matchesSearch = projectTitle.includes(searchTerm) ||
                projectTags.some(tag => tag.includes(searchTerm));

            if (matchesTags && matchesSearch) {
                project.classList.add("active");
            } else {
                project.classList.remove("active");
            }
        });
    }

    // Initialize all functionality
    initializeSorting();
    initializeTagFiltering();
    initializeSearch();
}