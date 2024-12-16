'use strict';

export class SkillsLoader {
    constructor(options = {}) {
        this.basePath = options.basePath || '../../content';
    }

    async loadSkills() {
        try {
            const response = await fetch(`${this.basePath}/profile/skills.md`);
            if (!response.ok) {
                console.error('HTTP Error:', response.status);
                throw new Error('Content not found');
            }
            const content = await response.json();
            return this.generateHTML(content);
        } catch (error) {
            console.error('Error loading skills:', error);
            console.error('Attempted path:', `${this.basePath}/profile/skills.md`);
            return null;
        }
    }

    generateHTML(content) {
        if (!content.config.showSkills) return '';

        const categoryOptions = content.categories.map(category =>
            `<option value="${category.toLowerCase()}">${category}</option>`
        ).join('');

        const tableRows = content.tools.map(tool => `
            <tr>
                <td data-label="Tool">
                    <div class="tool-cell">
                        <div class="tool-icon">
                            <ion-icon name="${tool.icon}"></ion-icon>
                        </div>
                        <span class="tool-name">${tool.name}</span>
                    </div>
                </td>
                <td data-label="Category">${tool.category}</td>
                <td data-label="Description">
                    <span class="details-snippet">${tool.description.substring(0, 50)}...</span>
                    <span class="details-full hidden">${tool.description}</span>
                    <a href="#" class="toggle-details" aria-expanded="false">more</a>
                </td>
            </tr>
        `).join('');

        const tableControls = `
            <div class="controls-wrapper">
                <div class="dataTables-controls">
                    <div class="filter-group">
                        <div class="dataTables_length">
                            <div class="select-wrapper">
                                <select id="customLengthSelect">
                                    <option value="5">5 entries</option>
                                    <option value="10">10 entries</option>
                                    <option value="25">25 entries</option>
                                    <option value="50">50 entries</option>
                                </select>
                                <ion-icon name="chevron-down-outline"></ion-icon>
                            </div>
                        </div>
                        
                        <div class="custom-filter">
                            <div class="select-wrapper">
                                <select id="categoryFilter">
                                    <option value="">All Categories</option>
                                    ${categoryOptions}
                                </select>
                                <ion-icon name="chevron-down-outline"></ion-icon>
                            </div>
                        </div>
                    </div>

                    <div class="search-box">
                        <div class="search-input-wrapper">
                            <ion-icon name="search-outline"></ion-icon>
                            <input type="text" id="customSearchBox" placeholder="Search tools...">
                        </div>
                    </div>
                </div>
            </div>
        `;

        return `
            <section class="skills">
                <h3 class="h3">Skills & Tools</h3>

                <div class="skills-content">
                    ${tableControls}

                    <table id="toolsTable" class="display">
                        <thead>
                            <tr>
                                <th>Tool</th>
                                <th>Category</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
            </section>
        `;
    }

    async renderSkills(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }

        if (!targetElement) {
            console.error('Target element not found');
            return;
        }

        const content = await this.loadSkills();
        if (content) {
            // First, destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#toolsTable')) {
                $('#toolsTable').DataTable().destroy();
            }

            // Then update the content
            targetElement.innerHTML = content;

            // Return a promise that resolves when content is loaded and ready for DataTable
            return new Promise((resolve) => {
                // Use setTimeout to ensure DOM is fully updated
                setTimeout(() => {
                    resolve();
                }, 0);
            });
        }
        return Promise.reject('No content loaded');
    }
}