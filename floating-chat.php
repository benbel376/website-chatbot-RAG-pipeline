<div class="floating-chat-thought" id="floatingChatThought">
    Anything I can help you with? 😊
</div>

<div class="floating-chat-button" id="floatingChatBtn">
    <ion-icon name="chatbubbles-outline"></ion-icon>
</div>

<div class="floating-chat-panel" id="floatingChatPanel">
    <div class="floating-chat-header">
        <h3 class="h3 form-title">Let's Chat</h3>
        <ion-icon name="close-outline" class="floating-chat-close" id="floatingChatClose"></ion-icon>
    </div>
  
    <div class="floating-chat-content">
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
</div> 