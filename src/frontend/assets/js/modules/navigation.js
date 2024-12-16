'use strict';

export function initNavigation() {
    console.log('Navigation initialization started');
    
    const navigationLinks = document.querySelectorAll("[data-nav-link]");
    const pages = document.querySelectorAll("[data-page]");
    
    console.log('Found nav links:', navigationLinks.length);
    console.log('Found pages:', pages.length);

    navigationLinks.forEach((link, linkIndex) => {
        link.addEventListener("click", function () {
            console.log('Nav link clicked:', this.innerHTML.toLowerCase());
            
            pages.forEach((page, pageIndex) => {
                console.log('Checking page:', page.dataset.page);
                
                if (this.innerHTML.toLowerCase() === page.dataset.page) {
                    console.log('Match found! Activating:', page.dataset.page);
                    page.classList.add("active");
                    navigationLinks[pageIndex].classList.add("active");
                    window.scrollTo(0, 0);
                } else {
                    page.classList.remove("active");
                    navigationLinks[pageIndex].classList.remove("active");
                }
            });
        });
    });
}