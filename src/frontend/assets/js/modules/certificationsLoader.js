'use strict';

export class CertificationsLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
        this.imagesPath = options.imagesPath || './assets/images';
    }

    async loadCertifications() {
        try {
            const response = await fetch(`${this.basePath}/profile/certifications.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading certifications:', error);
            console.error('Attempted path:', `${this.basePath}/profile/certifications.md`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showCertifications) return '';

        const slides = content.certifications.map((cert, index) => `
            <div class="certification-slide ${index === 0 ? 'active' : ''}">
                <div class="certification-card">
                    <div class="certification-image">
                        <img src="${this.imagesPath}/${cert.image}" alt="${cert.title} Logo">
                    </div>
                    <div class="certification-details">
                        <h4>${cert.title}</h4>
                        <p class="issuer">${cert.issuer}</p>
                        <p class="description">${cert.description}</p>
                        <a href="${cert.credentialUrl}" 
                           target="_blank" 
                           class="view-credential-btn">
                            View Credential
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </a>
                    </div>
                </div>
            </div>
        `).join('');

        const indicators = content.certifications.map((_, index) => `
            <div class="indicator ${index === 0 ? 'active' : ''}"></div>
        `).join('');

        return `
            <section class="certifications">
                <div class="certifications-slider" data-certifications-slider>
                    ${slides}
                </div>
                
                <div class="certification-controls">
                    <button class="control-btn prev" data-certification-prev>
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </button>
                    <div class="certification-indicators" data-certification-indicators>
                        ${indicators}
                    </div>
                    <button class="control-btn next" data-certification-next>
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </button>
                </div>
            </section>
        `;
    }

    async renderCertifications(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadCertifications();
        if (content) {
            targetElement.innerHTML = content;

            // Initialize certification slider after content is loaded
            import ('./certificationSlider.js').then(module => {
                module.default();
            });
        }
    }
}