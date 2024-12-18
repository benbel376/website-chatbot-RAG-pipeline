document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 6;
    const blogList = document.querySelector('.blog-posts-list');
    const blogItems = document.querySelectorAll('.blog-post-item');

    // Create pagination container
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'blog-pagination';
    blogList.parentNode.insertBefore(paginationContainer, blogList.nextSibling);

    const totalPages = Math.ceil(blogItems.length / itemsPerPage);
    let currentPage = 1;

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        blogItems.forEach((item, index) => {
            item.style.display = 'none';
            item.classList.remove('active');
            if (index >= start && index < end) {
                item.style.display = 'block';
                item.classList.add('active');
            }
        });
    }

    function createPaginationButtons() {
        paginationContainer.innerHTML = '';

        // Previous button
        const prevButton = document.createElement('button');
        prevButton.className = `page-button ${currentPage === 1 ? 'disabled' : ''}`;
        prevButton.innerHTML = '<ion-icon name="chevron-back-outline"></ion-icon>';
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });
        paginationContainer.appendChild(prevButton);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.className = `page-button ${i === currentPage ? 'active' : ''}`;
            button.textContent = i;
            button.addEventListener('click', () => {
                currentPage = i;
                updatePagination();
            });
            paginationContainer.appendChild(button);
        }

        // Next button
        const nextButton = document.createElement('button');
        nextButton.className = `page-button ${currentPage === totalPages ? 'disabled' : ''}`;
        nextButton.innerHTML = '<ion-icon name="chevron-forward-outline"></ion-icon>';
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
        paginationContainer.appendChild(nextButton);
    }

    function updatePagination() {
        showPage(currentPage);
        createPaginationButtons();
    }

    // Initial setup
    updatePagination();
});