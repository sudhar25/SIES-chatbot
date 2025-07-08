<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>FAQ Chatbot</title>
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
      width: 10cm;
      height: 15cm;
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
      height: 500px;
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
      0% { opacity: 0; transform: translateY(15px) scale(0.95); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }

      to { opacity: 1; transform: translateY(0); }
    

    #chat-controls {
      display: flex;
      border-top: 1px solid #ccc;
    }

    #chat-input {
      border-radius: 20px;
      padding: 10px;
      background: #e6f0ff;
      margin: 5px;
      font-size: 14px;
      border: 1px solid #ccc;
      outline: none;
    }

    #chat-box button {
      background: #007bff;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 10px 15px;
      margin: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    #close-chat {
      cursor: pointer;
      font-weight: bold;
    }
  
    @media (max-width: 600px) {
      #chat-box {
        width: 95%;
        height: 70vh;
      }
    }

</style>
</head>
<body>
<div id="chat-widget">
  <div id="chat-toggle" onclick="toggleChat()">Ask your queries</div>
  <div id="chat-box">
    <div id="chat-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <img src="bot.png" alt="Bot Logo" style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
        <span style="font-weight: bold;">Uthiran</span>
      </div>
      
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

  
  function quickAsk(message) {
    document.getElementById("chat-input").value = message;
    handleUserInput();
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