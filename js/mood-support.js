// Mood Support / AI Chat functionality

// Get the emotion from localStorage
const currentEmotion = localStorage.getItem('currentEmotion') || 'sad';

// Update emotion message based on current emotion
const emotionMessages = {
    sad: "I'm sorry you're feeling down. Let's work together to lift your spirits.",
    anxious: "I understand you're feeling anxious. Let's find some calming activities for you.",
    angry: "I hear you. Let's channel that energy into something positive.",
    disappointed: "Disappointment is tough. Let's find ways to help you feel better."
};

document.getElementById('emotionMessage').textContent = 
    emotionMessages[currentEmotion] || "Let's work together to lift your mood";

// Chat functionality
const chatMessages = document.getElementById('chatMessages');
const userMessageInput = document.getElementById('userMessage');
const sendMessageBtn = document.getElementById('sendMessage');

// AI responses based on emotion
const aiResponses = {
    sad: [
        "I'm here for you. Remember, it's okay to feel sad sometimes. Would you like to talk about what's bothering you?",
        "Sometimes watching something that makes us laugh can help. How about I suggest some uplifting videos?",
        "Taking a few deep breaths can help. Would you like to try a breathing exercise with me?"
    ],
    anxious: [
        "Anxiety can be overwhelming. Let's try some grounding techniques. Can you name 5 things you can see around you?",
        "Deep breathing can really help with anxiety. Would you like to try the 4-7-8 breathing technique?",
        "Sometimes, calming music or nature sounds can help ease anxiety. Shall I suggest some?"
    ],
    angry: [
        "It's okay to feel angry. Let's find a healthy way to process these feelings.",
        "Physical activity can help release that energy. How about a quick workout or a walk?",
        "Sometimes writing down what makes us angry helps. Would you like to journal about it?"
    ],
    disappointed: [
        "Disappointment is a natural feeling. What matters is how we move forward from it.",
        "Let's focus on something positive. What's one thing that went well today?",
        "Remember, setbacks are temporary. Would you like to talk about what disappointed you?"
    ]
};

let responseIndex = 0;

// Add message to chat
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

// Send message
function sendMessage() {
    const message = userMessageInput.value.trim();
    
    if (!message) return;
    
    // Add user message
    addMessage(message, true);
    userMessageInput.value = '';
    
    // Simulate AI response with delay
    setTimeout(() => {
        const responses = aiResponses[currentEmotion] || aiResponses.sad;
        const response = responses[responseIndex % responses.length];
        responseIndex++;
        
        addMessage(response);
    }, 1000);
}

sendMessageBtn.addEventListener('click', sendMessage);
userMessageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Recommendation functions
function playRecommendation(type) {
    let message = '';
    
    switch(type) {
        case 'calming-sounds':
            message = 'Opening calming sounds for you...';
            // In a real app, this would play actual audio
            setTimeout(() => {
                alert('🎵 Calming nature sounds are now playing...\n\nIn the full version, this would play soothing sounds like ocean waves, rain, or forest ambience.');
            }, 500);
            break;
        case 'videos':
            message = 'Finding uplifting videos for you...';
            setTimeout(() => {
                alert('😊 Here are some video suggestions:\n\n• Funny animal videos\n• Inspirational talks\n• Relaxing nature scenes\n\nIn the full version, these would be actual video links!');
            }, 500);
            break;
    }
    
    if (message) {
        addMessage(message);
    }
}

// Breathing exercise
let breathingInterval;
let breathingStep = 0;

function startBreathingExercise() {
    const modal = document.getElementById('breathingModal');
    const circle = document.getElementById('breathingCircle');
    const text = document.getElementById('breathingText');
    
    modal.style.display = 'flex';
    breathingStep = 0;
    
    const steps = [
        { text: 'Breathe in...', scale: 1.5, duration: 4000 },
        { text: 'Hold...', scale: 1.5, duration: 4000 },
        { text: 'Breathe out...', scale: 1, duration: 4000 },
        { text: 'Rest...', scale: 1, duration: 2000 }
    ];
    
    function updateBreathing() {
        const step = steps[breathingStep % steps.length];
        text.textContent = step.text;
        circle.style.transform = `scale(${step.scale})`;
        circle.style.transition = `transform ${step.duration}ms ease-in-out`;
        
        breathingStep++;
        
        breathingInterval = setTimeout(updateBreathing, step.duration);
    }
    
    updateBreathing();
}

function closeBreathingExercise() {
    const modal = document.getElementById('breathingModal');
    modal.style.display = 'none';
    clearTimeout(breathingInterval);
    
    addMessage("Great job completing the breathing exercise! How do you feel now?");
}

// Journal prompt
function showJournalPrompt() {
    const prompts = [
        "What is one thing that brought you joy recently?",
        "Describe a moment when you felt proud of yourself.",
        "What are you grateful for today?",
        "What would you tell a friend going through what you're experiencing?"
    ];
    
    const randomPrompt = prompts[Math.floor(Math.random() * prompts.length)];
    
    addMessage(`Here's a journaling prompt for you: "${randomPrompt}"\n\nTake your time and write in your private diary when you're ready.`);
    
    setTimeout(() => {
        if (confirm('Would you like to go to your diary now?')) {
            window.location.href = 'diary.html';
        }
    }, 1500);
}

// Add initial AI greeting
setTimeout(() => {
    const greetings = {
        sad: "I can see you're feeling sad. You're not alone, and it's brave of you to reach out. Let me help you feel better.",
        anxious: "I understand anxiety can feel overwhelming. Let's take this one step at a time together.",
        angry: "I hear you, and your feelings are valid. Let's find healthy ways to process this.",
        disappointed: "Disappointment is hard to deal with. I'm here to support you through this."
    };
    
    addMessage(greetings[currentEmotion] || greetings.sad);
}, 500);

// Detect if user seems very distressed and offer calming theme
setTimeout(() => {
    if (['anxious', 'angry'].includes(currentEmotion)) {
        if (!document.body.classList.contains('calm-theme')) {
            if (confirm('Would you like to switch to our calming theme? It uses softer colors and can help reduce stress.')) {
                document.body.classList.add('calm-theme');
                localStorage.setItem('calmTheme', 'true');
                addMessage("I've switched to the calming theme for you. I hope it helps you feel more at ease.");
            }
        }
    }
}, 3000);
