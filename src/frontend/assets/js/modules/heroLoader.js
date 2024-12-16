'use strict';

export class HeroLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
        this.imagesPath = options.imagesPath || './assets/images';
    }

    async loadHero() {
        try {
            const response = await fetch(`${this.basePath}/profile/hero.json`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading hero:', error);
            console.error('Attempted path:', `${this.basePath}/profile/hero.json`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showHero) return '';

        const contactLinks = content.hero.contacts.map(contact => {
            let href = contact.value;
            if (contact.type === 'email') href = `mailto:${contact.value}`;
            if (contact.type === 'phone') href = `tel:${contact.value}`;

            return `
                <a href="${href}" class="hero__icon" ${contact.type !== 'email' && contact.type !== 'phone' ? 'target="_blank"' : ''}>
                    <ion-icon name="${contact.icon}"></ion-icon>
                    <span>${contact.label}</span>
                </a>
            `;
        }).join('');

        return `
            <!-- Left Section: Avatar and Info -->
            <div class="hero__avatar-section">
                <figure class="hero__avatar">
                    <img src="${this.imagesPath}/${content.hero.avatar}" 
                         alt="${content.hero.name}" 
                         width="300">
                </figure>
                <div class="hero__info">
                    <h1 class="hero__name">${content.hero.name}</h1>
                    <p class="hero__title">${content.hero.title}</p>
                </div>
            </div>

            <!-- Right Section: Description and Social Links -->
            <div class="hero__content">
                <p class="hero__description">${content.hero.description}</p>
                <div class="hero__social-links">
                    ${contactLinks}
                </div>
            </div>
        `;
    }

    async renderHero(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadHero();
        if (content) {
            // Find the hero__container and update its content
            const heroContainer = targetElement.querySelector('.hero__container');
            if (heroContainer) {
                heroContainer.innerHTML = content;
            } else {
                console.error('Hero container not found');
            }
        }
    }
}