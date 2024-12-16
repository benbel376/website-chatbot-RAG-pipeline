'use strict';

export function initCollapsibleSections() {
    // Select only the experience experience items
    const experienceItems = document.querySelectorAll('.experience-list .experience-item');

    experienceItems.forEach(item => {
        // Get all bullet points in this experience item
        const bulletPoints = item.querySelectorAll('.experience-text li');

        // If there are more than 3 items, set up the collapse functionality
        if (bulletPoints.length > 3) {
            // Initially hide items beyond the first 3
            bulletPoints.forEach((bullet, index) => {
                if (index >= 3) {
                    bullet.style.display = 'none';
                }
            });

            // Check if a show more button already exists after this experience-text
            const experienceText = item.querySelector('.experience-text');
            const existingButton = experienceText.nextElementSibling;

            if (!existingButton || !existingButton.classList.contains('show-more-btn')) {
                // Create and append the "Show More" button only if it doesn't exist
                const showMoreBtn = document.createElement('button');
                showMoreBtn.className = 'show-more-btn';
                showMoreBtn.textContent = 'Show More';

                // Add button after the experience-text ul
                experienceText.insertAdjacentElement('afterend', showMoreBtn);

                // Add click event listener to the button
                showMoreBtn.addEventListener('click', function() {
                    const isExpanded = this.textContent === 'Show Less';

                    bulletPoints.forEach((bullet, index) => {
                        if (index >= 3) {
                            bullet.style.display = isExpanded ? 'none' : 'list-item';
                        }
                    });

                    this.textContent = isExpanded ? 'Show More' : 'Show Less';
                });
            }
        }
    });
}