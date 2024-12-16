'use strict';

export function initializeBlogs(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Get references to all required elements
    const blogItems = container.querySelectorAll(".blog-post-item");
    const blogListSection = container.querySelector("[data-content-list]");
    const blogDetailsSection = container.querySelector("[data-content-details]");
    const goBackBtn = container.querySelector("[data-go-back]");
    const detailUnits = container.querySelectorAll(".details-unit");
    const sortButtons = container.querySelectorAll(".blog-sort-btn");
    const selectButton = container.querySelector('[data-select]');
    const selectValue = container.querySelector('[data-select-value]');
    const selectList = container.querySelector('.blog-filter-list');
    const selectItems = container.querySelectorAll('[data-select-item]');
    const searchInput = container.querySelector('[data-search-input]');

    let selectedTags = [];

    // Initialize blog items click handlers
    blogItems.forEach((item) => {
        item.addEventListener("click", handleBlogClick);
    });

    function handleBlogClick(event) {
        event.preventDefault();
        const contentId = this.dataset.contentId;
        showBlogDetails(contentId);
    }

    function showBlogDetails(contentId) {
        detailUnits.forEach((unit) => {
            unit.hidden = true;
            unit.classList.remove("active");
        });

        const targetDetail = container.querySelector(`#${contentId}-details`);
        if (targetDetail) {
            targetDetail.hidden = false;
            targetDetail.classList.add("active");

            blogListSection.classList.add("hidden");
            blogDetailsSection.classList.add("active");

            goBackBtn.hidden = false;
            goBackBtn.classList.add("visible");
        }
    }

    // Handle go back button click
    goBackBtn.addEventListener("click", function() {
        blogDetailsSection.classList.remove("active");
        blogListSection.classList.remove("hidden");

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
        const sortedItems = Array.from(blogItems).sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return order === "asc" ? dateA - dateB : dateB - dateA;
        });
        renderSortedItems(sortedItems);
    }

    function sortByTitle(order) {
        const sortedItems = Array.from(blogItems).sort((a, b) => {
            const titleA = a.dataset.title ? a.dataset.title.toLowerCase() : '';
            const titleB = b.dataset.title ? b.dataset.title.toLowerCase() : '';
            return order === "asc" ? titleA.localeCompare(titleB) : titleB.localeCompare(titleA);
        });
        renderSortedItems(sortedItems);
    }

    function renderSortedItems(sortedItems) {
        const contentList = container.querySelector(".blog-posts-list");
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
                    filterBlogs();
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
            searchInput.addEventListener('input', filterBlogs);
        }
    }

    function filterBlogs() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

        blogItems.forEach((blog) => {
            const blogTags = blog.dataset.tags ? blog.dataset.tags.toLowerCase().split(",") : [];
            const blogTitle = blog.dataset.title ? blog.dataset.title.toLowerCase() : '';

            const matchesTags = selectedTags.length === 0 ||
                selectedTags.every(tag => blogTags.includes(tag.toLowerCase()));
            const matchesSearch = blogTitle.includes(searchTerm) ||
                blogTags.some(tag => tag.includes(searchTerm));

            if (matchesTags && matchesSearch) {
                blog.classList.remove("hidden");
                blog.classList.add("active");
            } else {
                blog.classList.add("hidden");
                blog.classList.remove("active");
            }
        });
    }

    // Initialize all functionality
    initializeSorting();
    initializeTagFiltering();
    initializeSearch();
}