// Daily Prompt functionality

// Array of daily prompts
const dailyPrompts = [
    "What are you grateful for today?",
    "Describe a moment that made you smile recently.",
    "What is one thing you accomplished today, no matter how small?",
    "Who in your life are you thankful for and why?",
    "What did you learn about yourself this week?",
    "What would you tell your younger self?",
    "Describe a place where you feel most at peace.",
    "What is something kind you did for yourself or someone else today?",
    "What challenges did you overcome this week?",
    "What are you looking forward to?",
    "What makes you feel strong?",
    "Describe a happy memory from your childhood.",
    "What is something you're proud of?",
    "How did you take care of yourself today?",
    "What positive changes have you noticed in yourself lately?"
];

// Get today's prompt based on date
function getTodaysPrompt() {
    const today = new Date();
    const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / 1000 / 60 / 60 / 24);
    return dailyPrompts[dayOfYear % dailyPrompts.length];
}

// Display current date
const currentDate = new Date();
document.getElementById('currentDate').textContent = currentDate.toLocaleDateString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
});

// Display today's prompt
document.getElementById('promptQuestion').textContent = getTodaysPrompt();

// Load saved reflections from localStorage
function loadReflections() {
    const reflections = JSON.parse(localStorage.getItem('dailyReflections') || '[]');
    return reflections;
}

// Save reflection
function saveReflection(answer) {
    const reflections = loadReflections();
    
    const reflection = {
        id: Date.now(),
        date: new Date().toISOString(),
        dateString: new Date().toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        }),
        prompt: getTodaysPrompt(),
        answer: answer
    };
    
    reflections.push(reflection);
    localStorage.setItem('dailyReflections', JSON.stringify(reflections));
    
    return reflection;
}

// Display weekly reflections
function displayWeeklyReflections() {
    const reflections = loadReflections();
    const container = document.getElementById('weeklyReflections');
    
    // Get reflections from the last 7 days
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
    
    const weeklyReflections = reflections.filter(r => {
        return new Date(r.date) >= oneWeekAgo;
    }).reverse();
    
    if (weeklyReflections.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📝</div>
                <p>No reflections yet this week. Start today!</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = weeklyReflections.map(reflection => `
        <div class="diary-entry fade-in">
            <div class="diary-date">${reflection.dateString}</div>
            <h4 style="color: var(--primary-purple); margin-bottom: 0.5rem; font-size: 1rem;">
                ${reflection.prompt}
            </h4>
            <div class="diary-content">${reflection.answer}</div>
        </div>
    `).join('');
    
    // Update completed count
    document.getElementById('completedCount').textContent = weeklyReflections.length;
}

// Submit prompt answer
document.getElementById('submitPrompt').addEventListener('click', function() {
    const answer = document.getElementById('promptAnswer').value.trim();
    
    if (!answer) {
        alert('Please write your reflection before submitting.');
        return;
    }
    
    // Check if already answered today
    const reflections = loadReflections();
    const today = new Date().toDateString();
    const answeredToday = reflections.some(r => 
        new Date(r.date).toDateString() === today
    );
    
    if (answeredToday) {
        if (!confirm('You\'ve already answered today\'s prompt. Would you like to add another reflection?')) {
            return;
        }
    }
    
    saveReflection(answer);
    
    // Show success message
    document.getElementById('successMessage').style.display = 'block';
    document.getElementById('promptAnswer').value = '';
    
    // Reload weekly reflections
    displayWeeklyReflections();
    
    // Hide success message after 5 seconds
    setTimeout(() => {
        document.getElementById('successMessage').style.display = 'none';
    }, 5000);
    
    // Scroll to weekly reflections
    setTimeout(() => {
        document.getElementById('weeklyReflections').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }, 500);
});

// Skip prompt
document.getElementById('skipPrompt').addEventListener('click', function() {
    if (confirm('Are you sure you want to skip today\'s reflection?')) {
        window.location.href = 'dashboard.html';
    }
});

// Load weekly reflections on page load
displayWeeklyReflections();

// Check if user came from dashboard and show encouraging message
if (document.referrer.includes('dashboard.html')) {
    setTimeout(() => {
        const motivationalMessages = [
            "Taking time to reflect shows real self-awareness. You're doing great!",
            "Every reflection is a step towards better understanding yourself.",
            "Your thoughts and feelings matter. Thank you for sharing them."
        ];
        
        const randomMessage = motivationalMessages[Math.floor(Math.random() * motivationalMessages.length)];
        
        // You could show this in a toast or modal
        console.log(randomMessage);
    }, 1000);
}
