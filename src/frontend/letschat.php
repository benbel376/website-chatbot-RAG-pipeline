<?php
// Contact Page Content
?>
<!--
    - #CONTACT
-->

<article class="contact" data-page="let's chat">

    <header>
        <h2 class="h2 article-title">Chat With Me</h2>
    </header>

    <section class="about-text">
        <p>Engage with my chatbot to learn more about my work, projects, experiences, and educational background. Start the conversation below!</p>
    </section>

    <div class="chat-container">
        <div class="chat-overlay"> <!-- Overlay added back -->
            <div class="chat-history">
                <div class="no-history">No chat history</div>
            </div>
        </div>
        <div class="chat-input">
            <button id="clear-history-btn"><ion-icon name="trash-outline"></ion-icon></button> <!-- Icon-only Clear button -->
            <input type="text" id="user-query" placeholder="Type your message here...">
            <button id="send-btn"><ion-icon name="send-outline"></ion-icon> Send</button> <!-- Send button with icon -->
        </div>
    </div>

    <section style="margin-top: 50px;" class="contact-form">
    <!-- Enhanced List Section with Interactive Prompts -->
    <ul class="card-list-type1">
        <li class="card-item-type2" data-prompt="Tell me about your projects">
            <div class="icon-text-container-type1">
                <ion-icon name="folder-outline" class="card-icon-type1" aria-label="Projects Icon"></ion-icon>
                <span class="card-text-type1">Ask me about my projects</span>
            </div>
            <ion-icon name="ellipsis-horizontal-outline" class="card-action-icon-type1" aria-label="More Options"></ion-icon>
        </li>
        <li class="card-item-type2" data-prompt="Share your work experiences">
            <div class="icon-text-container-type1">
                <ion-icon name="briefcase-outline" class="card-icon-type1" aria-label="Work Experiences Icon"></ion-icon>
                <span class="card-text-type1">Ask me about my work experiences</span>
            </div>
            <ion-icon name="ellipsis-horizontal-outline" class="card-action-icon-type1" aria-label="More Options"></ion-icon>
        </li>
        <li class="card-item-type2" data-prompt="Describe your educational background">
            <div class="icon-text-container-type1">
                <ion-icon name="school-outline" class="card-icon-type1" aria-label="Educational Background Icon"></ion-icon>
                <span class="card-text-type1">Ask me about my educational background</span>
            </div>
            <ion-icon name="ellipsis-horizontal-outline" class="card-action-icon-type1" aria-label="More Options"></ion-icon>
        </li>
        <li class="card-item-type2" data-prompt="Tell me anything about your work">
            <div class="icon-text-container-type1">
                <ion-icon name="information-circle-outline" class="card-icon-type1" aria-label="General Information Icon"></ion-icon>
                <span class="card-text-type1">Or ask anything about my work</span>
            </div>
            <ion-icon name="ellipsis-horizontal-outline" class="card-action-icon-type1" aria-label="More Options"></ion-icon>
        </li>
    </ul>
    <section>

</article>

</main>
