const FloatingChat = {
    init() {
        const chatBtn = document.getElementById('floatingChatBtn');
        const chatPanel = document.getElementById('floatingChatPanel');
        const closeBtn = document.getElementById('floatingChatClose');
        const thoughtBubble = document.getElementById('floatingChatThought');

        if (!chatBtn || !chatPanel || !closeBtn) return;

        chatBtn.addEventListener('click', () => {
            chatPanel.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            chatPanel.classList.remove('active');
        });

        // Close panel when clicking outside
        document.addEventListener('click', (e) => {
            if (!chatPanel.contains(e.target) && !chatBtn.contains(e.target)) {
                chatPanel.classList.remove('active');
            }
        });

        // Initialize interval time
        let currentInterval = 10000; // Start with 10 seconds

        // Function to trigger animation
        const triggerAnimation = () => {
            if (!chatPanel.classList.contains('active')) {
                chatBtn.classList.add('bounce');
                thoughtBubble.classList.add('show');

                // Remove bounce class after animation completes
                setTimeout(() => {
                    chatBtn.classList.remove('bounce');
                }, 2400);

                // Remove show class after thought bubble animation
                setTimeout(() => {
                    thoughtBubble.classList.remove('show');
                }, 4000);

                // Increase interval by 10 seconds for next animation
                currentInterval += 10000;

                // Schedule next animation with new interval
                setTimeout(triggerAnimation, currentInterval);
            } else {
                // If panel is active, check again after current interval
                setTimeout(triggerAnimation, currentInterval);
            }
        };

        // Start the first animation after initial 10 second delay
        setTimeout(triggerAnimation, currentInterval);
    }
};

export default FloatingChat;