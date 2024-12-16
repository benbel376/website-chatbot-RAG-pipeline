'use strict';

export function initContact() {
    const form = document.querySelector('.contact-form');
    const submitBtn = form?.querySelector('[type="submit"]');
    const successMessage = document.querySelector('.form-success');
    const errorMessage = document.querySelector('.form-error');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
        }

        try {
            const formData = new FormData(form);
            const response = await fetch('send_email.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                if (successMessage) successMessage.style.display = 'block';
                if (errorMessage) errorMessage.style.display = 'none';
                form.reset();
            } else {
                if (errorMessage) {
                    errorMessage.textContent = result.message || 'Failed to send message';
                    errorMessage.style.display = 'block';
                }
                if (successMessage) successMessage.style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            if (errorMessage) {
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.display = 'block';
            }
            if (successMessage) successMessage.style.display = 'none';
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Message';
            }
        }
    });
}
