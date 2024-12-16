export function initCollapse() {
    const triggers = document.querySelectorAll('.collapse-trigger');

    triggers.forEach(trigger => {
        // Set initial collapsed state
        const targetId = trigger.getAttribute('data-target');
        const content = document.getElementById(targetId);

        // Start in collapsed state
        content.classList.add('collapsed');
        trigger.setAttribute('aria-expanded', 'false');
        content.setAttribute('aria-hidden', 'true');

        trigger.addEventListener('click', () => {
            // Toggle the collapsed class
            content.classList.toggle('collapsed');

            // Update ARIA attributes
            const isCollapsed = content.classList.contains('collapsed');
            trigger.setAttribute('aria-expanded', !isCollapsed);
            content.setAttribute('aria-hidden', isCollapsed);
        });
    });
}