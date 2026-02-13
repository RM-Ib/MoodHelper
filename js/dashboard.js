// Dashboard functionality

let selectedEmotion = null;

// Emotion button handling
document.querySelectorAll('.emotion-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Remove selected class from all buttons
        document.querySelectorAll('.emotion-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        // Add selected class to clicked button
        this.classList.add('selected');
        selectedEmotion = this.getAttribute('data-emotion');
        
        // Show message option
        document.getElementById('messageOption').style.display = 'block';
        
        // Scroll to message option smoothly
        setTimeout(() => {
            document.getElementById('messageOption').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 100);
    });
});

// Submit feeling
document.getElementById('submitFeeling').addEventListener('click', function() {
    const feelingText = document.getElementById('feelingText').value;
    const sendMessage = document.getElementById('sendAnonymousMsg').checked;
    
    if (!selectedEmotion) {
        showToast('Please select how you\'re feeling', 'error');
        return;
    }
    
    // Save to local storage
    const entry = {
        emotion: selectedEmotion,
        text: feelingText,
        sendMessage: sendMessage,
        timestamp: new Date().toISOString(),
        date: new Date().toLocaleDateString()
    };
    
    // Get existing entries or initialize empty array
    let entries = JSON.parse(localStorage.getItem('moodEntries') || '[]');
    entries.push(entry);
    localStorage.setItem('moodEntries', JSON.stringify(entries));
    
    // Check if emotion is negative
    const negativeEmotions = ['sad', 'anxious', 'angry', 'disappointed'];
    
    if (negativeEmotions.includes(selectedEmotion)) {
        // Redirect to mood support page
        localStorage.setItem('currentEmotion', selectedEmotion);
        window.location.href = 'mood-support.html';
    } else {
        // Show positive confirmation
        showToast('Thank you for sharing! Keep tracking your mood to see your progress.');
        
        // Reset form
        document.getElementById('feelingText').value = '';
        document.getElementById('sendAnonymousMsg').checked = false;
        document.querySelectorAll('.emotion-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        document.getElementById('messageOption').style.display = 'none';
        selectedEmotion = null;
        
        // Optional: Show daily prompt
        setTimeout(() => {
            if (confirm('Would you like to answer today\'s reflection prompt?')) {
                window.location.href = 'daily-prompt.html';
            }
        }, 1500);
    }
});

// Toast notification helper (same as main.js)
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        font-weight: 500;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load and display greeting based on time of day
function setGreeting() {
    const hour = new Date().getHours();
    let greeting = 'Good day';
    
    if (hour < 12) greeting = 'Good morning';
    else if (hour < 18) greeting = 'Good afternoon';
    else greeting = 'Good evening';
    
    // You can use this greeting in your UI if needed
    console.log(greeting);
}

setGreeting();
