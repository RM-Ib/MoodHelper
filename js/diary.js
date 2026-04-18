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

function resetForm() {
    titleInput.value = '';
    contentInput.value = '';
    editingEntryId = null;
    todayEntry = null;
    entryFormHeading.textContent = '✍️ New Entry';
    saveEntryButton.textContent = 'Save Entry';
}

async function loadEntries() {
    try {
        const response = await fetch('Backend/get_diary_entries.php?filter=' + encodeURIComponent(filterEntries.value));
        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to load entries');
        }

        const entries = data.entries || [];

        totalEntriesElement.textContent = data.stats?.total_entries ?? 0;
        currentStreakElement.textContent = data.stats?.current_streak ?? 0;
        thisMonthElement.textContent = data.stats?.this_month ?? 0;

        if (entries.length === 0) {
            diaryEntriesContainer.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';

        todayEntry = entries.find(entry => {
            const today = new Date();
            const entryDate = new Date(entry.entry_date_raw + 'T00:00:00');
            return entryDate.toDateString() === today.toDateString();
        }) || null;

        diaryEntriesContainer.innerHTML = entries.map(entry => `
            <div class="diary-entry fade-in">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">
                    <div>
                        <div class="diary-date">${escapeHtml(entry.entry_date)}</div>
                        ${entry.title ? `<h4 style="margin: 0.5rem 0 0.75rem; color: var(--primary-purple); font-size: 1.1rem;">${escapeHtml(entry.title)}</h4>` : ''}
                    </div>
                    <div style="display:flex; gap:0.5rem;">
                        <button class="btn btn-secondary" onclick="editEntry(${entry.entry_id})">Edit</button>
                        <button class="btn btn-secondary" onclick="deleteEntry(${entry.entry_id})">Delete</button>
                    </div>
                </div>
                <div class="diary-content">${escapeHtml(entry.content).replace(/\n/g, '<br>')}</div>
            </div>
        `).join('');
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to load diary entries', 'error');
    }
}

async function saveEntry() {
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

        let url = 'Backend/save_diary_entry.php';

        if (editingEntryId) {
            formData.append('entry_id', editingEntryId);
            url = 'Backend/update_diary_entry.php';
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to save entry');
        }

        successMsg.style.display = 'block';
        showToast(data.message || 'Entry saved successfully');

        resetForm();
        await loadEntries();

        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 2500);
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to save entry', 'error');
    } finally {
        saveEntryButton.disabled = false;
        saveEntryButton.textContent = originalText;
    }
}

function editEntry(entryId) {
    const allEntries = Array.from(document.querySelectorAll('.diary-entry'));
    if (!allEntries.length) return;

    fetch('Backend/get_diary_entries.php?filter=all')
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                throw new Error(data.message || 'Failed to load entry');
            }

            const entry = (data.entries || []).find(e => Number(e.entry_id) === Number(entryId));
            if (!entry) {
                showToast('Entry not found', 'error');
                return;
            }

            editingEntryId = entry.entry_id;
            titleInput.value = entry.title || '';
            contentInput.value = entry.content || '';
            entryFormHeading.textContent = '✏️ Edit Entry';
            saveEntryButton.textContent = 'Update Entry';

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        })
        .catch(error => {
            console.error(error);
            showToast(error.message || 'Failed to load entry', 'error');
        });
}

async function deleteEntry(entryId) {
    if (!confirm('Are you sure you want to delete this diary entry?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('entry_id', entryId);

        const response = await fetch('Backend/delete_diary_entry.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to delete entry');
        }

        if (editingEntryId === entryId) {
            resetForm();
        }

        showToast(data.message || 'Entry deleted successfully');
        await loadEntries();
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to delete entry', 'error');
    }
}

saveEntryButton.addEventListener('click', saveEntry);

clearEntryButton.addEventListener('click', function () {
    resetForm();
});

filterEntries.addEventListener('change', loadEntries);

loadEntries();