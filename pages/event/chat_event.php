<?php
// Récupérer l'ID de l'événement et afficher le dashboard
$event_id = $_GET['event_id'];
?>
    <style>

        .chat-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 700px;
            display: flex;
            flex-direction: column;
            background: white;
            border-top: 2px solid #ddd;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .chat-messages .message {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        .chat-messages .message.user p {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            padding: 10px;
            border-radius: 15px 15px 0 15px;
            max-width: 70%;
        }

        .chat-messages .message.other p {
            background-color: #e1e1e1;
            align-self: flex-start;
            padding: 10px;
            border-radius: 15px 15px 0 15px;
            max-width: 70%;
        }

        .chat-footer {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        .chat-footer input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
            font-size: 14px;
        }

        .chat-footer button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-left: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .chat-footer button:hover {
            background-color: #0056b3;
        }

        .emoji-menu {
            position: absolute;
            bottom: 60px;
            left: 20px;
            display: none;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .emoji-menu button {
            background: none;
            border: none;
            font-size: 18px;
            margin: 5px;
            cursor: pointer;
        }
    </style>

    <div class="chat-container">
        <div class="chat-header">
            Chat évenement
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Messages -->
        </div>
        <div class="chat-footer">
            <input type="text" id="messageInput" placeholder="Écrivez un message...">
            <button onclick="sendMessage()">Envoyer</button>
            <button onclick="toggleEmojiMenu()">😊</button>
        </div>
    </div>

    <div class="emoji-menu" id="emojiMenu">
        <button onclick="addEmoji('😊')">😊</button>
        <button onclick="addEmoji('😂')">😂</button>
        <button onclick="addEmoji('❤️')">❤️</button>
        <button onclick="addEmoji('👍')">👍</button>
        <button onclick="addEmoji('🎉')">🎉</button>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const messageInput = document.getElementById('messageInput');
        const emojiMenu = document.getElementById('emojiMenu');

        function sendMessage() {
            const messageText = messageInput.value.trim();
            if (messageText !== '') {
                const message = document.createElement('div');
                message.classList.add('message', 'user');
                message.innerHTML = `<p>${messageText}</p>`;
                chatMessages.appendChild(message);

                messageInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Simulate a response
                setTimeout(() => {
                    const response = document.createElement('div');
                    response.classList.add('message', 'other');
                    response.innerHTML = `<p>Réponse automatique</p>`;
                    chatMessages.appendChild(response);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        }

        function toggleEmojiMenu() {
            emojiMenu.style.display = emojiMenu.style.display === 'block' ? 'none' : 'block';
        }

        function addEmoji(emoji) {
            messageInput.value += emoji;
            emojiMenu.style.display = 'none';
        }
    </script>