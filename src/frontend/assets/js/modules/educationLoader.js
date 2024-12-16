'use strict';

export class EducationLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
    }

    async loadEducation() {
        try {
            const response = await fetch(`${this.basePath}/profile/education.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading education:', error);
            console.error('Attempted path:', `${this.basePath}/profile/education.md`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showEducation) return '';

        const educationItems = content.education.map(edu => {
            const details = edu.details.map(detail =>
                `<li>${detail}</li>`
            ).join('');

            return `
                <li class="education-item">
                    <h4 class="h4 education-item-title">${edu.degree}</h4>
                    <span>${edu.institution}</span>
                    <span>${edu.period}</span>
                    <div class="education-text">
                        <ul>
                            ${details}
                        </ul>
                    </div>
                </li>
            `;
        }).join('');

        return `
            <section class="education">
                <div class="education-header">
                    <div class="icon-box">
                        <ion-icon name="school-outline"></ion-icon>
                    </div>
                    <h3 class="h3">Education</h3>
                </div>

                <ul class="education-list">
                    ${educationItems}
                </ul>
            </section>
        `;
    }

    async renderEducation(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadEducation();
        if (content) {
            targetElement.innerHTML = content;

            // Initialize collapsible sections if needed
            import ('./collapsibleSections.js').then(module => {
                module.initCollapsibleSections();
            });
        }
    }
}