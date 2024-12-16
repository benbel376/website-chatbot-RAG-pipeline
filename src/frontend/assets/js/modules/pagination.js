'use strict';

export function initPagination(container) {
    let currentPage = 1;
    const pages = container.querySelectorAll('.blog-page');
    const totalPages = pages.length;
    const prevBtn = container.querySelector('[data-blog-prev-page]');
    const nextBtn = container.querySelector('[data-blog-next-page]');
    const paginationInfo = container.querySelector('.blog-pagination-info');

    const updatePagination = () => {
        paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;

        pages.forEach((page, index) => {
            if (index + 1 === currentPage) {
                page.classList.add('active');
            } else {
                page.classList.remove('active');
            }
        });
    };

    prevBtn?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });

    nextBtn?.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    });

    updatePagination();
}
