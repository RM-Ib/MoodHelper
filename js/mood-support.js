// =============================
// CHAT + MEMORY + MOOD
// =============================

const chatMessages = document.getElementById('chatMessages');
const userMessageInput = document.getElementById('userMessage');
const sendMessageBtn = document.getElementById('sendMessage');

let chatHistory = [];
// =============================
// EXPAND CHAT
// =============================

const chatContainer = document.querySelector('.chat-container');
const expandBtn = document.getElementById('expandChatBtn');
const messages = document.getElementById('chatMessages');

let expanded = false;

function restoreExpandButton() {
    if (!expandBtn) return;

    expandBtn.style.display = 'block';
    expandBtn.style.visibility = 'visible';
    expandBtn.style.opacity = '1';
    expandBtn.style.position = 'absolute';
    expandBtn.style.top = '10px';
    expandBtn.style.right = '10px';
    expandBtn.style.zIndex = '100000';
    expandBtn.style.background = 'none';
    expandBtn.style.border = 'none';
    expandBtn.style.cursor = 'pointer';
    expandBtn.style.color = '#9ca3af';
    expandBtn.style.fontSize = '1.1rem';
}

if (expandBtn && chatContainer) {
    expandBtn.textContent = '⛶';
    restoreExpandButton();

    expandBtn.addEventListener('click', () => {
        expanded = !expanded;

        if (expanded) {
            document.body.style.overflow = 'hidden';

            chatContainer.style.position = 'fixed';
            chatContainer.style.top = '0';
            chatContainer.style.left = '0';
            chatContainer.style.right = '0';
            chatContainer.style.bottom = '0';
            chatContainer.style.width = '100vw';
            chatContainer.style.height = '100vh';
            chatContainer.style.maxWidth = '100vw';
            chatContainer.style.margin = '0';
            chatContainer.style.padding = '20px';
            chatContainer.style.zIndex = '99999';
            chatContainer.style.borderRadius = '0';
            chatContainer.style.display = 'flex';
            chatContainer.style.flexDirection = 'column';
            chatContainer.style.background = '#fff';
            chatContainer.style.boxSizing = 'border-box';
            chatContainer.style.overflow = 'hidden';

            if (messages) {
                messages.style.flex = '1';
                messages.style.height = 'auto';
                messages.style.minHeight = '0';
                messages.style.overflowY = 'auto';
                messages.style.overflowX = 'hidden';
            }

            expandBtn.textContent = '⤡';
            restoreExpandButton();
        } else {
            document.body.style.overflow = '';

            chatContainer.style.position = '';
            chatContainer.style.top = '';
            chatContainer.style.left = '';
            chatContainer.style.right = '';
            chatContainer.style.bottom = '';
            chatContainer.style.width = '';
            chatContainer.style.height = '';
            chatContainer.style.maxWidth = '';
            chatContainer.style.margin = '';
            chatContainer.style.padding = '';
            chatContainer.style.zIndex = '';
            chatContainer.style.borderRadius = '';
            chatContainer.style.display = '';
            chatContainer.style.flexDirection = '';
            chatContainer.style.background = '';
            chatContainer.style.boxSizing = '';
            chatContainer.style.overflow = '';

            if (messages) {
                messages.style.flex = '';
                messages.style.height = '';
                messages.style.minHeight = '';
                messages.style.overflowY = '';
                messages.style.overflowX = '';
            }

            expandBtn.textContent = '⛶';
            restoreExpandButton();
        }
    });
}

userMessageInput.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
// =============================
// NAVIGATION
// =============================

function goToDiary() {
    window.location.href = 'diary.php';
}

function goToDashboard() {
    window.location.href = 'dashboard.php';
}

// =============================
// BREATHING EXERCISE
// =============================

let breathingInterval;

function startBreathingExercise() {
    const modal = document.getElementById('breathingModal');
    const circle = document.getElementById('breathingCircle');
    const text = document.getElementById('breathingText');

    modal.style.display = 'flex';

    let inhale = true;

    breathingInterval = setInterval(() => {
        if (inhale) {
            circle.style.transform = 'scale(1.3)';
            text.textContent = 'Breathe in...';
        } else {
            circle.style.transform = 'scale(1)';
            text.textContent = 'Breathe out...';
        }
        inhale = !inhale;
    }, 4000);
}

function closeBreathingExercise() {
    const modal = document.getElementById('breathingModal');
    modal.style.display = 'none';

    clearInterval(breathingInterval);
}

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

        // scroll to bottom on load
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 50);

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

    // typing indicator
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'message ai fade-in';
    loadingMsg.innerHTML = `
        <div class="message-header">MoodHelper AI</div>
        <div>Typing...</div>
    `;
    chatMessages.appendChild(loadingMsg);

    // scroll immediately after user message
    chatMessages.scrollTop = chatMessages.scrollHeight;

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

        // ✅ smooth scroll AFTER AI reply
        setTimeout(() => {
            chatMessages.scrollTo({
                top: chatMessages.scrollHeight,
                behavior: 'smooth'
            });

                    
            userMessageInput.focus();
        

            if (typeof restoreExpandButton === 'function') {
                restoreExpandButton();
            }
        }, 50);

    } catch (error) {
    chatMessages.removeChild(loadingMsg);
    addMessage("⚠️ Error connecting to AI.");
    

    if (typeof restoreExpandButton === 'function') {
        restoreExpandButton();
    }

    console.error(error);
}
}

// =============================
// EVENTS
// =============================

sendMessageBtn.addEventListener('click', sendMessage);

userMessageInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault(); // 🚫 stop new line
        sendMessage();      // ✅ send message
    }
});



// =============================
// INIT
// =============================

window.onload = loadMessages;