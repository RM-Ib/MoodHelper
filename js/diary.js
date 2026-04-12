let selectedMood = '';
let editingEntryId = null;

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

    saveEntryButton.disabled = true;
    const originalText = saveEntryButton.textContent;
    saveEntryButton.textContent = editingEntryId ? 'Updating...' : 'Saving...';

    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        formData.append('mood', selectedMood);

        let endpoint = 'Backend/save_diary_entry.php';

        if (editingEntryId) {
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

        resetForm();
        await loadDiaryEntries(filterEntries.value);

        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 3000);
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to save entry.', 'error');
    } finally {
        saveEntryButton.disabled = false;
        saveEntryButton.textContent = editingEntryId ? 'Update Entry' : originalText;
    }
});

clearEntryButton.addEventListener('click', function () {
    resetForm();
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

        if (entries.length === 0) {
            diaryEntriesContainer.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';

        diaryEntriesContainer.innerHTML = entries.map(entry => `
            <div class="card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <div>
                        <h3 style="margin: 0 0 0.35rem 0; font-size: 1.25rem;">
                            ${escapeHtml(entry.title || 'Untitled Entry')}
                        </h3>
                        <div style="color: var(--text-secondary); font-size: 0.95rem;">
                            ${escapeHtml(entry.entry_date)}
                        </div>
                    </div>

                    ${entry.mood ? `
                        <span style="background: rgba(124, 58, 237, 0.08); color: var(--primary-purple); padding: 0.35rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 500; text-transform: capitalize;">
                            ${escapeHtml(entry.mood)}
                        </span>
                    ` : ''}
                </div>

                <div style="line-height: 1.8; color: var(--text-primary); white-space: pre-wrap; margin-bottom: 1rem;">
                    ${escapeHtml(entry.content)}
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary diary-edit-btn" data-entry-id="${entry.entry_id}">
                        Edit
                    </button>
                    <button type="button" class="btn btn-secondary diary-delete-btn" data-entry-id="${entry.entry_id}">
                        Delete
                    </button>
                </div>
            </div>
        `).join('');

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

            saveEntryButton.textContent = 'Update Entry';
            entryFormHeading.textContent = '✍️ Edit Entry';

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
                    resetForm();
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

function resetForm() {
    editingEntryId = null;
    selectedMood = '';
    titleInput.value = '';
    contentInput.value = '';
    moodButtons.forEach(btn => btn.classList.remove('selected'));
    saveEntryButton.textContent = 'Save Entry';
    entryFormHeading.textContent = '✍️ New Entry';
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

loadDiaryEntries();