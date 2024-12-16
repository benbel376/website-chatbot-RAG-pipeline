'use strict';

export class TestimonialsLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
        this.imagesPath = options.imagesPath || './assets/images';
    }

    async loadTestimonials() {
        try {
            const response = await fetch(`${this.basePath}/profile/testimonials.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading testimonials:', error);
            console.error('Attempted path:', `${this.basePath}/profile/testimonials.md`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showTestimonials) return '';

        const testimonialItems = content.testimonials.map(testimonial => `
            <li class="testimonials-item">
                <div class="content-card" data-testimonials-item>
                    <figure class="testimonials-avatar-box">
                        <img src="${this.imagesPath}/${testimonial.avatar}" 
                             alt="${testimonial.name}" 
                             width="60" 
                             data-testimonials-avatar>
                    </figure>

                    <h4 class="h4 testimonials-item-title" data-testimonials-title>${testimonial.name}</h4>

                    <div class="testimonials-text" data-testimonials-text>
                        <h3>${testimonial.title}</h3>
                        <p>
                            ${testimonial.text}
                            <a href="${testimonial.linkedin}">linkedin</a>
                        </p>
                    </div>
                </div>
            </li>
        `).join('');

        const modalContent = `
            <div class="modal-container" data-modal-container>
                <div class="overlay" data-overlay></div>

                <section class="testimonials-modal">
                    <button class="modal-close-btn" data-modal-close-btn>
                        <ion-icon name="close-outline"></ion-icon>
                    </button>

                    <div class="modal-img-wrapper">
                        <figure class="modal-avatar-box">
                            <img src="" alt="modal img" width="80" data-modal-img>
                        </figure>

                        <img src="./assets/images/icon-quote.svg" alt="quote icon">
                    </div>

                    <div class="modal-content">
                        <h4 class="h3 modal-title" data-modal-title></h4>
                        <time datetime="2021-06-14">14 June, 2021</time>
                        <div data-modal-text></div>
                    </div>
                </section>
            </div>
        `;

        return `
            <section class="testimonials">
                <ul class="testimonials-list has-scrollbar">
                    ${testimonialItems}
                </ul>
                ${modalContent}
            </section>
        `;
    }

    async renderTestimonials(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadTestimonials();
        if (content) {
            targetElement.innerHTML = content;

            // Initialize testimonials functionality after content is loaded
            import ('./testimonials.js').then(module => {
                module.initTestimonials();
            });
        }
    }
}