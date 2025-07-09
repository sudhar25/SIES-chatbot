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
  background-color: #ff6600;
  color: white;
  padding: 10px 15px;
  border-radius: 50px;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  font-weight: bold;
  transition: background-color 0.3s;
  display: flex; 
  align-items: center; 
  gap: 20px; 
}

 .chat-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%; 
  object-fit: cover;
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
      backdrop-filter: blur(8px);
      background: rgba(255, 255, 255, 0.85);
    }

    #chat-header {
      background-color: #ff6600; 
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
      line-height: 1.6;
    }

    .bot-msg {
      align-self: flex-start;
      background-color: #e0e0e0;
      border-radius: 16px 16px 16px 0;
      line-height: 1.6;
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(15px) scale(0.95); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    #chat-controls {
      display: flex;
      align-items: center;
      padding: 10px;
      border-top: 1px solid #ccc;
      gap: 10px;
    }

    #chat-input {
      flex: 1;
      border-radius: 20px;
      padding: 10px;
      background: #e6f0ff;
      margin: 5px;
      font-size: 14px;
      border: 1px solid #ccc;
      outline: none;
    }

    #chat-box button {
      background: #ff6600; 
      color: white;
      border: none;
      border-radius: 20px;
      padding: 10px 15px;
      margin: 5px;
      font-weight: bold;
      cursor: pointer;
      white-space: nowrap;
    }

    #close-chat {
  cursor: pointer;
  font-weight: bold;
  font-size: 20px; /* Increase text size */
  padding: 6px 12px; /* Add padding for clickable area */
  background-color: #ffffff;
  color: #ff6600;
  border-radius: 50%;
  border: 2px solid #ff6600;
  transition: all 0.3s ease;
}

  #close-chat:hover {
  background-color: #ff6600;
  color: white;
  transform: scale(1.1);
}


    #chat-toggle:hover {
    background-color:rgb(191, 94, 24);
    transform: scale(1.05);
  }


    .typing-indicator {
  display: inline-flex;
  gap: 4px;
  padding: 10px 14px;
  background-color:  #e0e0e0;
  border-radius: 16px 16px 16px 0;
  align-self: flex-start;
}

   .typing-indicator span {
  width: 6px;
  height: 6px;
  background: #888;
  border-radius: 50%;
  animation: blink 1.2s infinite ease-in-out;
}

    .typing-indicator span:nth-child(2) {
  animation-delay: 0.2s;
}
    .typing-indicator span:nth-child(3) {
  animation-delay: 0.4s;
}

    @keyframes blink {
  0%, 80%, 100% {
    opacity: 0.2;
    transform: scale(0.8);
  }
  40% {
    opacity: 1;
    transform: scale(1);
  }
}


    @keyframes slideUp {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

#chat-box {
  animation: slideUp 0.4s ease-out;
}


    @media (max-width: 600px) {
  #chat-toggle {
    font-size: 14px;
    padding: 8px 12px;
    gap: 10px;
  }

  #chat-input {
    font-size: 12px;
    padding: 8px;
  }

  .chat-icon {
    width: 30px;
    height: 30px;
  }
}
</style>
</head>
<body>
<div id="chat-widget">
  <div id="chat-toggle" onclick="toggleChat()">
    <img src="bot.png" alt="Chat Icon" class="chat-icon">
    Ask your queries
  </div>
  <div id="chat-box">
    <div id="chat-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <img src="logo.png" alt="Bot Logo" style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
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

  // Show typing indicator
  const chatBody = document.getElementById("chat-body");
  const typingIndicator = document.createElement("div");
  typingIndicator.className = "bot-msg typing-indicator";
  typingIndicator.innerHTML = '<span></span><span></span><span></span>';
  chatBody.appendChild(typingIndicator);
  chatBody.scrollTop = chatBody.scrollHeight;

  setTimeout(() => {
   fetch("chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "message=" + encodeURIComponent(userText)
    })
    .then(res => res.text())
    .then(reply => {
      chatBody.removeChild(typingIndicator);
      addMessage("Bot: " + reply, 'bot-msg');
    })
    .catch(() => {
      chatBody.removeChild(typingIndicator);
      addMessage("Bot: Something went wrong. Please try again.", 'bot-msg');
    });
  }, 800);

}



  function addMessage(text, className) {
    const chatBody = document.getElementById("chat-body");
    const bubble = document.createElement("div");
    bubble.className = className;
    bubble.innerHTML = text;
    chatBody.appendChild(bubble);
    chatBody.scrollTop = chatBody.scrollHeight;
  }
  document.getElementById("chat-input").addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      handleUserInput();
    }
  });
  
</script>
</body>
</html>
