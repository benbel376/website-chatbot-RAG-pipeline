'use strict';

export class SummaryLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
        this.imagesPath = options.imagesPath || './assets/images';
    }

    async loadSummary() {
        try {
            const response = await fetch(`${this.basePath}/profile/summary.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading summary:', error);
            console.error('Attempted path:', `${this.basePath}/profile/summary.md`);
            return null;
        }
    }

    generateHTML(content) {
            // Generate About Text Section
            const aboutText = `
            <section class="about-text">
                ${content.introduction.map(para => `<p>${para}</p>`).join('')}
            </section>
        `;

        // Generate Services Section
        const servicesList = content.services.map(service => `
            <li class="service-item">
                <div class="service-icon-box">
                    <img src="${service.icon}" alt="${service.title} icon" width="40">
                </div>
                <div class="service-content-box">
                    <h4 class="h4 service-item-title">${service.title}</h4>
                </div>
            </li>
        `).join('');

        const servicesSection = `
            <section class="service">
                <h4 class="h4 service-title">What I Do</h4>
                <ul class="service-list">
                    ${servicesList}
                </ul>
            </section>
        `;

        // Generate Slideshow Section
        const slides = content.systemDiagrams.slides.map((slide, index) => `
            <div class="slideshow-slide ${index === 0 ? 'active-dot' : ''}">
                <img src="${this.imagesPath}/${slide.image}" alt="${slide.title}">
                <div class="slide-caption">
                    <h3>${slide.title}</h3>
                    <p>${slide.description}</p>
                </div>
            </div>
        `).join('');

        const dots = content.systemDiagrams.slides.map((_, index) => `
            <span class="dot ${index === 0 ? 'active-dot' : ''}" data-dot="${index}"></span>
        `).join('');

        const slideshowSection = `
            <section class="slideshow-section">
                <h4 class="h4 service-title">${content.systemDiagrams.title}</h4>
                <div class="slideshow-outer-container">
                    <div class="slideshow-container">
                        ${slides}
                        <button class="slideshow-btn prev" data-prev>
                            <ion-icon name="chevron-back-outline"></ion-icon>
                        </button>
                        <button class="slideshow-btn next" data-next>
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </button>
                    </div>
                    <div class="dots-container">
                        ${dots}
                    </div>
                </div>
            </section>
        `;

        return aboutText + servicesSection + slideshowSection;
    }

    async renderSummary(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadSummary();
        if (content) {
            targetElement.innerHTML = content;
            
            // Initialize slideshow after content is loaded
            const slideshowContainer = targetElement.querySelector('.slideshow-outer-container');
            if (slideshowContainer) {
                import('./slideshow.js').then(module => {
                    module.initSlideshow(slideshowContainer);
                });
            }
        }
    }
}