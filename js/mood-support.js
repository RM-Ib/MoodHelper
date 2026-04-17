// =============================
// CHAT + MEMORY + MOOD
// =============================

const chatMessages = document.getElementById('chatMessages');
const userMessageInput = document.getElementById('userMessage');
const sendMessageBtn = document.getElementById('sendMessage');

let chatHistory = [];

// =============================
// ADD MESSAGE TO UI
// =============================
function addMessage(content, isUser = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user' : 'ai'} fade-in`;

    if (!isUser) {
        const header = document.createElement('div');
        header.className = 'message-header';
        header.textContent = 'MoodHelper AI';
        messageDiv.appendChild(header);
    }

    const contentDiv = document.createElement('div');
    contentDiv.textContent = content;
    messageDiv.appendChild(contentDiv);

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// =============================
// LOAD MESSAGES FROM DB
// =============================
async function loadMessages() {
    try {
        const response = await fetch("http://localhost/moodhelper/Backend/load-messages.php");
        const data = await response.json();

        chatHistory = data;

        data.forEach(msg => {
            addMessage(msg.content, msg.role === "user");
        });

    } catch (error) {
        console.error("Load error:", error);
    }
}

// =============================
// MOOD DETECTION
// =============================
function detectMood(msg) {
    msg = msg.toLowerCase();

    if (msg.includes("exam") || msg.includes("nervous") || msg.includes("stress"))
        return "stress";

    if (msg.includes("sad") || msg.includes("down"))
        return "sad";

    if (msg.includes("angry") || msg.includes("mad"))
        return "anger";

    return "normal";
}

// =============================
// SEND MESSAGE
// =============================
async function sendMessage() {
    const message = userMessageInput.value.trim();
    if (!message) return;

    addMessage(message, true);
    userMessageInput.value = '';

    const mood = detectMood(message);

    chatHistory.push({
        role: "user",
        content: message
    });

    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'message ai fade-in';
    loadingMsg.innerHTML = `<div class="message-header">MoodHelper AI</div><div>Typing...</div>`;
    chatMessages.appendChild(loadingMsg);

    try {
        const response = await fetch("http://localhost/moodhelper/Backend/ai-chat.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                history: chatHistory,
                mood: mood
            })
        });

        const data = await response.json();

        chatMessages.removeChild(loadingMsg);

        const reply = data.reply || "Something went wrong.";
        addMessage(reply);

        chatHistory.push({
            role: "assistant",
            content: reply
        });

    } catch (error) {
        chatMessages.removeChild(loadingMsg);
        addMessage("⚠️ Error connecting to AI.");
        console.error(error);
    }
}

// =============================
// EVENTS
// =============================
sendMessageBtn.addEventListener('click', sendMessage);

userMessageInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// 🚀 LOAD ON START
window.onload = loadMessages;