export function initWelcomeOverlay() {
    const overlay = document.getElementById('welcomeOverlay');
    const enterButton = document.getElementById('enterButton');
    const wizardImage = document.querySelector('.wizard-image');
    const welcomeCard = document.querySelector('.welcome-card');
    const body = document.body;
    let chatPanel = null;
    let thoughtBubble = null;

    function createChatElements() {
        // Create thought bubble
        thoughtBubble = document.createElement('div');
        thoughtBubble.className = 'wizard-thought-bubble';
        thoughtBubble.textContent = 'How may I be of service? 🧙‍♂️';
        document.body.appendChild(thoughtBubble);

        // Create chat panel
        chatPanel = document.createElement('div');
        chatPanel.className = 'wizard-chat-panel';
        chatPanel.innerHTML = `
            <div class="wizard-chat-header">
                <h3 class="h3 form-title">Let's Chat</h3>
                <ion-icon name="close-outline" class="wizard-chat-close"></ion-icon>
            </div>
            <div class="wizard-chat-content">
                <p class="form-text">
                    Let's discuss your project and see how I can help you.
                </p>
                <form class="form" action="https://formspree.io/f/xoqgkgjw" method="POST">
                    <div class="input-wrapper">
                        <input type="text" name="fullname" class="form-input" placeholder="Full name" required>
                        <input type="email" name="email" class="form-input" placeholder="Email address" required>
                    </div>
                    <textarea name="message" class="form-input" placeholder="Your Message" required></textarea>
                    <button class="form-btn" type="submit">
                        <ion-icon name="paper-plane"></ion-icon>
                        <span>Send Message</span>
                    </button>
                </form>
            </div>
        `;
        document.body.appendChild(chatPanel);

        // Add event listeners for chat panel
        const closeBtn = chatPanel.querySelector('.wizard-chat-close');
        closeBtn.addEventListener('click', () => {
            chatPanel.classList.remove('show-chat');
        });
    }

    function handleWizardHover(wizardAssistant) {
        if (!thoughtBubble.classList.contains('show')) {
            thoughtBubble.classList.add('show');
            setTimeout(() => {
                thoughtBubble.classList.remove('show');
            }, 3000);
        }
    }

    function toggleChat(wizardAssistant) {
        // Add click animation
        wizardAssistant.classList.add('clicked');
        setTimeout(() => {
            wizardAssistant.classList.remove('clicked');
        }, 500);

        // Toggle chat panel with animation
        if (!chatPanel.classList.contains('show-chat')) {
            chatPanel.style.display = 'block';
            setTimeout(() => {
                chatPanel.classList.add('show-chat');
            }, 10);
        } else {
            chatPanel.classList.remove('show-chat');
            setTimeout(() => {
                chatPanel.style.display = 'none';
            }, 500);
        }
    }

    function transitionWizard() {
        // Create wizard assistant element but keep it hidden
        const wizardAssistant = document.createElement('div');
        wizardAssistant.className = 'wizard-assistant';
        wizardAssistant.innerHTML = `<img src="${wizardImage.src}" alt="Wizard Assistant">`;
        wizardAssistant.style.opacity = '0';
        document.body.appendChild(wizardAssistant);

        // Get initial position of wizard
        const initialRect = wizardImage.getBoundingClientRect();

        // Force a reflow to ensure the initial position is captured
        void wizardImage.offsetWidth;

        // Start the transition by adding the class first
        wizardImage.classList.add('transitioning');

        // Then fade out the overlay and card
        overlay.classList.add('fade-out');
        welcomeCard.style.opacity = '0';

        // After transition completes
        wizardImage.addEventListener('transitionend', function handler(e) {
            if (e.propertyName !== 'transform') return;

            wizardImage.removeEventListener('transitionend', handler);
            wizardAssistant.style.opacity = '1';
            wizardAssistant.style.display = 'flex';
            wizardImage.style.display = 'none';
            body.classList.add('wizard-active');
            overlay.style.display = 'none';

            // Create chat elements
            createChatElements();

            // Add event listeners with proper binding
            wizardAssistant.addEventListener('mouseenter', () => handleWizardHover(wizardAssistant));
            wizardAssistant.addEventListener('click', () => toggleChat(wizardAssistant));
        });
    }

    function hideOverlay() {
        // Ensure the wizard is in its initial position before starting transition
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                transitionWizard();
            });
        });
    }

    // Handle button click
    enterButton.addEventListener('click', hideOverlay);

    // Handle Enter key press
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !overlay.classList.contains('fade-out')) {
            hideOverlay();
        }
    });
}