let selectedMood = '';
let editingEntryId = null;
let todayEntry = null;

const titleInput = document.getElementById('entryTitle');
const contentInput = document.getElementById('entryContent');
const saveEntryButton = document.getElementById('saveEntry');
const clearEntryButton = document.getElementById('clearEntry');
const successMsg = document.getElementById('successMsg');
const filterEntries = document.getElementById('filterEntries');
const diaryEntriesContainer = document.getElementById('diaryEntries');
const emptyState = document.getElementById('emptyState');
const totalEntriesElement = document.getElementById('totalEntries');
const currentStreakElement = document.getElementById('currentStreak');
const thisMonthElement = document.getElementById('thisMonth');
const entryFormHeading = document.getElementById('entryFormHeading');

const moodButtons = document.querySelectorAll('.emotion-btn');

const moodMeta = {
    happy: { label: 'Happy', emoji: '😊' },
    sad: { label: 'Sad', emoji: '😢' },
    anxious: { label: 'Anxious', emoji: '😰' },
    calm: { label: 'Calm', emoji: '😌' },
    grateful: { label: 'Grateful', emoji: '🙏' },
    angry: { label: 'Angry', emoji: '😠' },
    neutral: { label: 'Neutral', emoji: '😐' },
    disappointed: { label: 'Disappointed', emoji: '😞' }
};

moodButtons.forEach(button => {
    button.addEventListener('click', function () {
        moodButtons.forEach(btn => btn.classList.remove('selected'));
        this.classList.add('selected');
        selectedMood = this.getAttribute('data-mood');
    });
});

saveEntryButton.addEventListener('click', async function () {
    const title = titleInput.value.trim();
    const content = contentInput.value.trim();

    if (!content) {
        showToast('Please write something before saving.', 'error');
        return;
    }

    if (!selectedMood) {
        showToast('Please choose a mood before saving.', 'error');
        return;
    }

    const isEditingOldEntry = editingEntryId !== null;
    const originalText = saveEntryButton.textContent;

    saveEntryButton.disabled = true;
    saveEntryButton.textContent = isEditingOldEntry ? 'Updating...' : (todayEntry ? 'Updating...' : 'Saving...');

    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        formData.append('mood', selectedMood);

        let endpoint = 'Backend/save_diary_entry.php';

        if (isEditingOldEntry) {
            formData.append('entry_id', editingEntryId);
            endpoint = 'Backend/update_diary_entry.php';
        }

        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData
        });

        const rawText = await response.text();
        console.log('diary save/update raw response:', rawText);

        let data;
        try {
            data = JSON.parse(rawText);
        } catch (e) {
            throw new Error('Invalid server response: ' + rawText);
        }

        if (data.status !== 'success') {
            throw new Error(data.message || 'Could not save entry');
        }

        successMsg.style.display = 'block';
        showToast(data.message || 'Diary entry saved successfully.');

        editingEntryId = null;
        await loadDiaryEntries('all');

        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 3000);
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to save entry.', 'error');
    } finally {
        saveEntryButton.disabled = false;
        updateFormMode();
    }
});

clearEntryButton.addEventListener('click', function () {
    if (editingEntryId !== null) {
        editingEntryId = null;
        loadTodayEntryIntoForm();
        showToast('Returned to today’s entry.');
        return;
    }

    if (todayEntry) {
        loadTodayEntryIntoForm();
        showToast('Today’s saved entry restored.');
        return;
    }

    resetFormCompletely();
    successMsg.style.display = 'none';
});

filterEntries.addEventListener('change', function () {
    loadDiaryEntries(this.value);
});

async function loadDiaryEntries(filter = 'all') {
    try {
        const response = await fetch(`Backend/get_diary_entries.php?filter=${encodeURIComponent(filter)}`);
        const rawText = await response.text();
        console.log('get_diary_entries raw response:', rawText);

        let data;
        try {
            data = JSON.parse(rawText);
        } catch (e) {
            throw new Error('Invalid server response: ' + rawText);
        }

        if (data.status !== 'success') {
            throw new Error(data.message || 'Could not load diary entries');
        }

        const entries = data.entries || [];
        const stats = data.stats || {};

        totalEntriesElement.textContent = stats.total_entries ?? 0;
        currentStreakElement.textContent = stats.current_streak ?? 0;
        thisMonthElement.textContent = stats.this_month ?? 0;

        detectTodayEntry(entries);

        if (editingEntryId === null) {
            if (todayEntry) {
                loadTodayEntryIntoForm();
            } else {
                resetFormCompletely();
            }
        }

        if (entries.length === 0) {
            diaryEntriesContainer.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';

        diaryEntriesContainer.innerHTML = entries.map(entry => {
            const moodInfo = moodMeta[entry.mood] || null;
            const isToday = isTodayEntry(entry);

          return `
<div style="
    background: white;
    border-radius: 1.5rem;
    padding: 1.4rem 1.5rem;
    margin-bottom: 1.2rem;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
    border-left: 4px solid #7c3aed;
">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.8rem;
        flex-wrap: wrap;
    ">
        <div>
            <div style="
                display: flex;
                align-items: center;
                gap: 0.6rem;
                flex-wrap: wrap;
                margin-bottom: 0.15rem;
            ">
                <h3 style="
                    margin: 0;
                    font-size: 1.35rem;
                    font-weight: 700;
                    color: #0f172a;
                ">
                    ${escapeHtml(entry.title || 'Untitled Entry')}
                </h3>

                ${isToday ? `
                    <span style="
                        background: rgba(59, 130, 246, 0.1);
                        color: #2563eb;
                        padding: 0.25rem 0.6rem;
                        border-radius: 999px;
                        font-size: 0.75rem;
                        font-weight: 600;
                    ">
                        Today
                    </span>
                ` : ''}
            </div>

            <div style="
                color: #64748b;
                font-size: 0.9rem;
            ">
                ${escapeHtml(entry.entry_date)}
            </div>
        </div>

        ${moodInfo ? `
            <span style="
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                background: rgba(124, 58, 237, 0.08);
                color: #7c3aed;
                padding: 0.45rem 0.8rem;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 600;
            ">
                <span>${moodInfo.emoji}</span>
                <span>${escapeHtml(moodInfo.label)}</span>
            </span>
        ` : ''}
    </div>

    <div style="
        color: #1e293b;
        line-height: 1.6;
        font-size: 0.95rem;
        white-space: pre-wrap;
        margin-bottom: 1rem;
    ">
        ${escapeHtml(entry.content)}
    </div>

    <div style="
        display: flex;
        gap: 0.6rem;
    ">
        <button
            type="button"
            class="diary-edit-btn"
            data-entry-id="${entry.entry_id}"
            style="
                border: 1.5px solid #d1d5db;
                background: white;
                color: #7c3aed;
                border-radius: 999px;
                padding: 0.6rem 1rem;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
            "
        >
            Edit
        </button>

        <button
            type="button"
            class="diary-delete-btn"
            data-entry-id="${entry.entry_id}"
            style="
                border: 1.5px solid #d1d5db;
                background: white;
                color: #7c3aed;
                border-radius: 999px;
                padding: 0.6rem 1rem;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
            "
        >
            Delete
        </button>
    </div>
</div>
`;}).join('');

        attachEntryActionListeners(entries);
    } catch (error) {
        console.error(error);
        diaryEntriesContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #b91c1c;">
                Failed to load diary entries.
            </div>
        `;
        emptyState.style.display = 'none';
    }
}

function detectTodayEntry(entries) {
    const todayLabel = getTodayLabel();
    todayEntry = entries.find(entry => entry.entry_date === todayLabel) || null;
}

function getTodayLabel() {
    return new Date().toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
}

function isTodayEntry(entry) {
    return entry.entry_date === getTodayLabel();
}

function loadTodayEntryIntoForm() {
    if (!todayEntry) {
        resetFormCompletely();
        return;
    }

    titleInput.value = todayEntry.title || '';
    contentInput.value = todayEntry.content || '';
    selectedMood = todayEntry.mood || '';
    editingEntryId = null;

    moodButtons.forEach(btn => {
        btn.classList.remove('selected');
        if (btn.getAttribute('data-mood') === selectedMood) {
            btn.classList.add('selected');
        }
    });

    updateFormMode();
}

function updateFormMode() {
    if (editingEntryId !== null) {
        entryFormHeading.textContent = '✍️ Edit Entry';
        saveEntryButton.textContent = 'Update Entry';
        clearEntryButton.textContent = 'Cancel Edit';
        return;
    }

    if (todayEntry) {
        entryFormHeading.textContent = '✍️ Today’s Entry';
        saveEntryButton.textContent = 'Update Entry';
        clearEntryButton.textContent = 'Reset';
        return;
    }

    entryFormHeading.textContent = '✍️ New Entry';
    saveEntryButton.textContent = 'Save Entry';
    clearEntryButton.textContent = 'Clear';
}

function attachEntryActionListeners(entries) {
    document.querySelectorAll('.diary-edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const entryId = parseInt(this.getAttribute('data-entry-id'), 10);
            const entry = entries.find(item => Number(item.entry_id) === entryId);

            if (!entry) return;

            editingEntryId = entryId;
            titleInput.value = entry.title || '';
            contentInput.value = entry.content || '';
            selectedMood = entry.mood || '';

            moodButtons.forEach(btn => {
                btn.classList.remove('selected');
                if (btn.getAttribute('data-mood') === selectedMood) {
                    btn.classList.add('selected');
                }
            });

            updateFormMode();

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            showToast('Entry loaded for editing.');
        });
    });

    document.querySelectorAll('.diary-delete-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const entryId = parseInt(this.getAttribute('data-entry-id'), 10);

            if (!confirm('Are you sure you want to delete this entry?')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('entry_id', entryId);

                const response = await fetch('Backend/delete_diary_entry.php', {
                    method: 'POST',
                    body: formData
                });

                const rawText = await response.text();
                console.log('delete_diary_entry raw response:', rawText);

                let data;
                try {
                    data = JSON.parse(rawText);
                } catch (e) {
                    throw new Error('Invalid server response: ' + rawText);
                }

                if (data.status !== 'success') {
                    throw new Error(data.message || 'Could not delete entry');
                }

                if (editingEntryId === entryId) {
                    editingEntryId = null;
                }

                if (todayEntry && Number(todayEntry.entry_id) === entryId) {
                    todayEntry = null;
                }

                showToast(data.message || 'Entry deleted successfully.');
                await loadDiaryEntries(filterEntries.value);
            } catch (error) {
                console.error(error);
                showToast(error.message || 'Failed to delete entry.', 'error');
            }
        });
    });
}

function resetFormCompletely() {
    editingEntryId = null;
    selectedMood = '';
    titleInput.value = '';
    contentInput.value = '';
    moodButtons.forEach(btn => btn.classList.remove('selected'));
    updateFormMode();
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        z-index: 10000;
        font-weight: 600;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

loadDiaryEntries();