// Private Diary functionality

let selectedMood = null;

// Load diary entries from localStorage
function loadEntries() {
    return JSON.parse(localStorage.getItem('diaryEntries') || '[]');
}

// Save entry to localStorage
function saveEntry(entry) {
    const entries = loadEntries();
    entries.push(entry);
    localStorage.setItem('diaryEntries', JSON.stringify(entries));
    return entries;
}

// Delete entry
function deleteEntry(id) {
    if (!confirm('Are you sure you want to delete this entry? This cannot be undone.')) {
        return;
    }
    
    let entries = loadEntries();
    entries = entries.filter(entry => entry.id !== id);
    localStorage.setItem('diaryEntries', JSON.stringify(entries));
    displayEntries();
    updateStats();
    showToast('Entry deleted');
}

// Edit entry
function editEntry(id) {
    const entries = loadEntries();
    const entry = entries.find(e => e.id === id);
    
    if (!entry) return;
    
    // Populate form
    document.getElementById('entryTitle').value = entry.title;
    document.getElementById('entryContent').value = entry.content;
    
    // Select mood
    document.querySelectorAll('.emotion-btn').forEach(btn => {
        btn.classList.remove('selected');
        if (btn.getAttribute('data-mood') === entry.mood) {
            btn.classList.add('selected');
            selectedMood = entry.mood;
        }
    });
    
    // Delete old entry
    let updatedEntries = entries.filter(e => e.id !== id);
    localStorage.setItem('diaryEntries', JSON.stringify(updatedEntries));
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    showToast('Entry loaded for editing');
}

// Display entries
function displayEntries(filter = 'all') {
    const container = document.getElementById('diaryEntries');
    const emptyState = document.getElementById('emptyState');
    let entries = loadEntries().reverse(); // Most recent first
    
    // Apply filter
    const now = new Date();
    if (filter === 'week') {
        const oneWeekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
        entries = entries.filter(e => new Date(e.date) >= oneWeekAgo);
    } else if (filter === 'month') {
        const oneMonthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
        entries = entries.filter(e => new Date(e.date) >= oneMonthAgo);
    }
    
    if (entries.length === 0) {
        container.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    container.style.display = 'flex';
    emptyState.style.display = 'none';
    
    const moodEmojis = {
        happy: '😊',
        sad: '😢',
        anxious: '😰',
        calm: '😌',
        grateful: '🙏'
    };
    
    container.innerHTML = entries.map(entry => `
        <div class="diary-entry fade-in">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                <div>
                    <div class="diary-date">
                        ${new Date(entry.date).toLocaleDateString('en-US', { 
                            weekday: 'long',
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </div>
                    ${entry.title ? `<h3 style="margin-top: 0.5rem; font-size: 1.25rem; color: var(--text-primary);">${entry.title}</h3>` : ''}
                </div>
                <div style="font-size: 2rem;">${moodEmojis[entry.mood] || '📝'}</div>
            </div>
            <div class="diary-content">${entry.content}</div>
            <div class="diary-actions">
                <button class="btn btn-secondary btn-small" onclick="editEntry(${entry.id})">
                    Edit
                </button>
                <button class="btn btn-secondary btn-small" onclick="deleteEntry(${entry.id})">
                    Delete
                </button>
            </div>
        </div>
    `).join('');
}

// Update statistics
function updateStats() {
    const entries = loadEntries();
    
    // Total entries
    document.getElementById('totalEntries').textContent = entries.length;
    
    // Entries this month
    const now = new Date();
    const thisMonth = entries.filter(e => {
        const entryDate = new Date(e.date);
        return entryDate.getMonth() === now.getMonth() && 
               entryDate.getFullYear() === now.getFullYear();
    }).length;
    document.getElementById('thisMonth').textContent = thisMonth;
    
    // Calculate streak
    let streak = 0;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    for (let i = 0; i < 365; i++) {
        const checkDate = new Date(today);
        checkDate.setDate(today.getDate() - i);
        
        const hasEntry = entries.some(e => {
            const entryDate = new Date(e.date);
            entryDate.setHours(0, 0, 0, 0);
            return entryDate.getTime() === checkDate.getTime();
        });
        
        if (hasEntry) {
            streak++;
        } else if (i > 0) {
            break;
        }
    }
    
    document.getElementById('currentStreak').textContent = streak;
}

// Mood button handling
document.querySelectorAll('.emotion-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.emotion-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        this.classList.add('selected');
        selectedMood = this.getAttribute('data-mood');
    });
});

// Save entry button
document.getElementById('saveEntry').addEventListener('click', function() {
    const title = document.getElementById('entryTitle').value.trim();
    const content = document.getElementById('entryContent').value.trim();
    
    if (!content) {
        alert('Please write something before saving.');
        return;
    }
    
    const entry = {
        id: Date.now(),
        title: title,
        content: content,
        mood: selectedMood || 'neutral',
        date: new Date().toISOString()
    };
    
    saveEntry(entry);
    
    // Show success message
    document.getElementById('successMsg').style.display = 'block';
    setTimeout(() => {
        document.getElementById('successMsg').style.display = 'none';
    }, 3000);
    
    // Clear form
    document.getElementById('entryTitle').value = '';
    document.getElementById('entryContent').value = '';
    document.querySelectorAll('.emotion-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
    selectedMood = null;
    
    // Refresh display
    displayEntries();
    updateStats();
    
    // Scroll to entries
    setTimeout(() => {
        document.getElementById('diaryEntries').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }, 500);
});

// Clear entry button
document.getElementById('clearEntry').addEventListener('click', function() {
    if (confirm('Are you sure you want to clear this entry?')) {
        document.getElementById('entryTitle').value = '';
        document.getElementById('entryContent').value = '';
        document.querySelectorAll('.emotion-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        selectedMood = null;
    }
});

// Filter entries
document.getElementById('filterEntries').addEventListener('change', function() {
    displayEntries(this.value);
});

// Toast notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        background: #10b981;
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
    }, 2000);
}

// Initialize on page load
displayEntries();
updateStats();

// Motivational message for new users
const entries = loadEntries();
if (entries.length === 0) {
    setTimeout(() => {
        console.log('Welcome to your private diary! This is your safe space to express yourself freely.');
    }, 1000);
}
