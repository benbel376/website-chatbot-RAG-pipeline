'use strict';

export function initCodeBlockCopy() {
    function copyCode(button) {
        // Find the closest code block container
        const container = button.closest('.code-block-container');
        if (!container) return;

        // Find the code block and get all lines
        const codeBlock = container.querySelector('.code-block');
        const codeLines = codeBlock.querySelectorAll('.code-line');

        // Extract and join the text content from each line
        const codeText = Array.from(codeLines)
            .map(line => line.textContent)
            .join('\n');

        // Copy to clipboard
        navigator.clipboard.writeText(codeText).then(() => {
            // Visual feedback
            button.classList.add('copied');
            button.querySelector('.button-text').textContent = 'Copied!';

            // Reset after 2 seconds
            setTimeout(() => {
                button.classList.remove('copied');
                button.querySelector('.button-text').textContent = 'Copy';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy code:', err);
            button.querySelector('.button-text').textContent = 'Failed to copy';
        });
    }

    // Add click handlers to all copy buttons
    document.addEventListener('click', (e) => {
        if (e.target.closest('.copy-button')) {
            copyCode(e.target.closest('.copy-button'));
        }
    });
}