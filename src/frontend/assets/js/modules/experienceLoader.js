'use strict';

export class ExperienceLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
    }

    async loadExperience() {
        try {
            const response = await fetch(`${this.basePath}/profile/experience.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading experience:', error);
            console.error('Attempted path:', `${this.basePath}/profile/experience.md`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showExperience) return '';

        const experienceItems = content.experiences.map(exp => {
            const highlights = exp.highlights.map(highlight =>
                `<li>${highlight}</li>`
            ).join('');

            return `
                <li class="experience-item">
                    <h4 class="h4 experience-item-title">${exp.title}</h4>
                    <span>${exp.company}</span>
                    <span>${exp.period}</span>
                    <div class="experience-text">
                        <ul>
                            ${highlights}
                        </ul>
                    </div>
                </li>
            `;
        }).join('');

        return `
            <section class="experience">
                <div class="experience-header">
                    <div class="icon-box">
                        <ion-icon name="book-outline"></ion-icon>
                    </div>
                    <h3 class="h3">Experience</h3>
                </div>

                <ul class="experience-list">
                    ${experienceItems}
                </ul>
            </section>
        `;
    }

    async renderExperience(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadExperience();
        if (content) {
            targetElement.innerHTML = content;

            // Initialize collapsible sections if needed
            import ('./collapsibleSections.js').then(module => {
                module.initCollapsibleSections();
            });
        }
    }
}