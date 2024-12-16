'use strict';

export function initChat() {
    const cardItems = document.querySelectorAll('.card-item-type2');
    const userQueryInput = document.getElementById('user-query');
    const sendButton = document.getElementById('send-btn');
    const chatHistory = document.querySelector('.chat-history');
    const clearHistoryBtn = document.getElementById('clear-history-btn');

    if (!userQueryInput || !sendButton || !chatHistory || !clearHistoryBtn) return;

    function sendUserQuery() {
        const userQuery = userQueryInput.value;
        if (userQuery.trim() !== '') {
            addMessage(userQuery, 'message-box', 'user');
            showLoadingAnimation();
            generateBotResponse(userQuery);
            userQueryInput.value = '';
        }
    }

    function addMessage(text, className, sender) {
        const chatHistory = document.querySelector('.chat-history');
        const messageDiv = document.createElement('div');
        const messageContent = document.createElement('div');
        const iconBox = document.createElement('div');

        messageDiv.classList.add(sender === 'user' ? 'chat-message' : 'chat-response');
        messageContent.classList.add(className);
        iconBox.classList.add('icon-box');
        iconBox.textContent = sender === 'user' ? 'U' : 'B';

        messageContent.textContent = text;

        if (sender === 'bot') {
            messageDiv.appendChild(iconBox);
            messageDiv.appendChild(messageContent);
        } else {
            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(iconBox);
        }

        chatHistory.appendChild(messageDiv);

        document.querySelector('.no-history').style.display = 'none';

        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function showLoadingAnimation() {
        const loadingDiv = document.createElement('div');
        const loadingAnimation = document.createElement('span');
        
        loadingDiv.classList.add('chat-response');
        loadingAnimation.classList.add('loading-animation');
        
        let dotCount = 0;
        const interval = setInterval(() => {
            dotCount = (dotCount + 1) % 4;
            loadingAnimation.textContent = '.'.repeat(dotCount);
        }, 500);

        loadingDiv.appendChild(loadingAnimation);
        chatHistory.appendChild(loadingDiv);
        loadingDiv.setAttribute('data-interval', interval);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function generateBotResponse(userQuery) {
        fetch('https://bot.biniyambelayneh.com/query/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: userQuery,
                top_k: 3
            })
        })
        .then(response => response.json())
        .then(data => {
            const botResponse = data.answer || "I'm sorry, I couldn't find an answer to your question.";
            removeLoadingAnimation();
            addMessage(botResponse, 'response-box', 'bot');
        })
        .catch(error => {
            console.error('Error:', error);
            removeLoadingAnimation();
            addMessage("Oops! Something went wrong. Please try again.", 'response-box', 'bot');
        });
    }

    function removeLoadingAnimation() {
        const loadingElement = document.querySelector('.loading-animation');
        if (loadingElement) {
            const intervalId = loadingElement.parentElement.getAttribute('data-interval');
            clearInterval(intervalId);
            loadingElement.parentElement.remove();
        }
    }

    function clearHistory() {
        chatHistory.innerHTML = '<div class="no-history">No chat history</div>';
    }

    sendButton.addEventListener('click', sendUserQuery);
    userQueryInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendUserQuery();
        }
    });
    clearHistoryBtn.addEventListener('click', clearHistory);

    cardItems.forEach(item => {
        item.addEventListener('click', () => {
            const prompt = item.getAttribute('data-prompt');
            if (prompt) {
                userQueryInput.value = prompt;
                userQueryInput.focus();
            }
        });
    });
}
