<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SIES FAQ Chatbot</title>
  <style>
    #chat-widget {
      position: fixed;
      bottom: 20px;
      right: 20px;
      font-family: Arial, sans-serif;
      z-index: 9999;
    }

    #chat-toggle {
      background-color: #004080;
      color: white;
      padding: 10px 15px;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      font-weight: bold;
      transition: background-color 0.3s;
    }

    #chat-box {
      width: 320px;
      background: #fff;
      border: 1px solid #ccc;
      margin-top: 10px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      display: none;
      flex-direction: column;
    }

    #chat-header {
      background-color: #004080;
      color: white;
      padding: 10px;
      border-radius: 10px 10px 0 0;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    #chat-body {
      height: 200px;
      overflow-y: auto;
      padding: 10px;
      font-size: 14px;
      background-color: #f5f5f5;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .user-msg, .bot-msg {
      padding: 10px 14px;
      max-width: 80%;
      border-radius: 16px;
      animation: fadeIn 0.3s ease-in;
    }

    .user-msg {
      align-self: flex-end;
      background-color: #cce5ff;
      border-radius: 16px 16px 0 16px;
    }

    .bot-msg {
      align-self: flex-start;
      background-color: #e0e0e0;
      border-radius: 16px 16px 16px 0;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    #chat-controls {
      display: flex;
      border-top: 1px solid #ccc;
    }

    #chat-input {
      width: 65%;
      border: none;
      padding: 8px;
      outline: none;
    }

    #chat-box button {
      width: 35%;
      background-color: rgb(75, 31, 251);
      color: white;
      border: none;
      padding: 8px;
      cursor: pointer;
    }

    #close-chat {
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div id="chat-widget">
  <div id="chat-toggle" onclick="toggleChat()">Ask your queries</div>
  <div id="chat-box">
    <div id="chat-header">
      SIES FAQ Chatbot
      <span id="close-chat" onclick="toggleChat()">×</span>
    </div>
    <div id="chat-body"></div>
    <div id="chat-controls">
      <input type="text" id="chat-input" placeholder="Ask something..." />
      <button onclick="handleUserInput()">Send</button>
    </div>
  </div>
</div>

<script>
  function toggleChat() {
    const box = document.getElementById('chat-box');
    box.style.display = box.style.display === 'none' ? 'flex' : 'none';
  }

  function handleUserInput() {
    const input = document.getElementById('chat-input');
    const userText = input.value.trim();
    if (!userText) return;

    addMessage("You: " + userText, 'user-msg');
    input.value = '';

    fetch("chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "message=" + encodeURIComponent(userText)
    })
    .then(res => res.text())
    .then(reply => {
      addMessage("Bot: " + reply, 'bot-msg');
    })
    .catch(() => {
      addMessage("Bot: Something went wrong. Please try again.", 'bot-msg');
    });
  }

  function addMessage(text, className) {
    const chatBody = document.getElementById("chat-body");
    const bubble = document.createElement("div");
    bubble.className = className;
    bubble.textContent = text;
    chatBody.appendChild(bubble);
    chatBody.scrollTop = chatBody.scrollHeight;

    
  document.getElementById("chat-input").addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      handleUserInput();
    }
  });
  }
</script>
</body>
</html>