/* =========================
   GLOBAL STATE
========================= */

let selectedEmotion = null;
let currentPromptId = null;
let alreadyAnsweredToday = false;

/* =========================
   ELEMENTS
========================= */

const emotionButtons = document.querySelectorAll('.emotion-btn');

const messageOption = document.getElementById('messageOption');
const messageTextWrapper = document.getElementById('messageTextWrapper');

const moodNote = document.getElementById('moodNote');
const peerMessage = document.getElementById('peerMessage');

const sendAnonymousMsg = document.getElementById('sendAnonymousMsg');

const submitFeelingButton = document.getElementById('submitFeeling');
const successMessage = document.getElementById('successMessage');

const receivedMessagesContainer = document.getElementById('receivedMessages');

/* Daily Prompt */
const dailyPromptModal = document.getElementById('dailyPromptModal');
const closeDailyPromptModal = document.getElementById('closeDailyPromptModal');

const currentDateElement = document.getElementById('currentDate');
const promptQuestionElement = document.getElementById('promptQuestion');
const promptAnswerElement = document.getElementById('promptAnswer');

const submitPromptButton = document.getElementById('submitPrompt');
const skipPromptButton = document.getElementById('skipPrompt');

const promptSuccessMessageElement = document.getElementById('promptSuccessMessage');
const weeklyReflectionsElement = document.getElementById('weeklyReflections');
const completedCountElement = document.getElementById('completedCount');

/* =========================
   HELPERS
========================= */

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
        z-index: 9999;
        font-weight: 500;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = '0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

async function fetchJson(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();

    let data;
    try {
        data = JSON.parse(text);
    } catch {
        throw new Error("Invalid server response");
    }

    if (!res.ok || data.status !== 'success') {
        throw new Error(data.message || 'Request failed');
    }

    return data;
}

/* =========================
   MOOD SELECTION
========================= */

emotionButtons.forEach(btn => {
    btn.addEventListener('click', function () {
        emotionButtons.forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');

        selectedEmotion = this.dataset.emotion;

        messageOption.style.display = 'block';
    });
});

/* =========================
   CHECKBOX → SHOW MESSAGE
========================= */

sendAnonymousMsg.addEventListener('change', function () {
    if (this.checked) {
        messageTextWrapper.style.display = 'block';
    } else {
        messageTextWrapper.style.display = 'none';
        peerMessage.value = '';
    }
});

/* =========================
   SUBMIT CHECK-IN
========================= */

submitFeelingButton.addEventListener('click', async function () {

    const note = moodNote.value.trim();
    const message = peerMessage.value.trim();
    const sendMessage = sendAnonymousMsg.checked;

    if (!selectedEmotion) {
        showToast('Please choose a mood first.', 'error');
        return;
    }

    if (sendMessage && message === '') {
        showToast('Write your message before sending it.', 'error');
        return;
    }

    submitFeelingButton.disabled = true;
    submitFeelingButton.textContent = 'Submitting...';

    try {
        const formData = new FormData();
        formData.append('mood', selectedEmotion);
        formData.append('notes', note);
        formData.append('peer_message', message);
        formData.append('send_message', sendMessage ? '1' : '0');

        const data = await fetchJson('Backend/save_dashboard_checkin.php', {
            method: 'POST',
            body: formData
        });

        showToast(data.message);

        successMessage.style.display = 'block';

        /* redirect if negative mood */
        const negative = ['sad', 'anxious', 'angry', 'disappointed'];
        if (negative.includes(selectedEmotion)) {
            setTimeout(() => {
                window.location.href = 'mood-support.php';
            }, 1200);
        }

        /* reset */
        moodNote.value = '';
        peerMessage.value = '';
        sendAnonymousMsg.checked = false;
        messageTextWrapper.style.display = 'none';
        messageOption.style.display = 'none';
        emotionButtons.forEach(b => b.classList.remove('selected'));
        selectedEmotion = null;

        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);

        loadReceivedMessages();

    } catch (err) {
        console.error(err);
        showToast(err.message, 'error');
    }

    submitFeelingButton.disabled = false;
    submitFeelingButton.textContent = 'Submit';
});

/* =========================
   LOAD RECEIVED MESSAGES
========================= */

async function loadReceivedMessages() {
    try {
        const res = await fetch('Backend/get_received_messages.php');
        const data = await res.json();

        if (data.status !== 'success') {
            throw new Error();
        }

        const messages = data.messages || [];

        if (messages.length === 0) {
            return;
        }

        receivedMessagesContainer.innerHTML = messages.map(m => `
            <div class="card" style="margin-bottom:1rem;">
                <div style="font-size:0.9rem; color:#6b7280; margin-bottom:0.5rem;">
                    ${escapeHtml(m.created_at)}
                </div>
                <div style="font-weight:500; margin-bottom:0.5rem;">
                    Mood: ${escapeHtml(m.mood)}
                </div>
                <div>
                    ${escapeHtml(m.message_text)}
                </div>
            </div>
        `).join('');

    } catch (e) {
        console.error(e);
    }
}

/* =========================
   DAILY PROMPT
========================= */

function openPrompt() {
    dailyPromptModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePrompt() {
    dailyPromptModal.style.display = 'none';
    document.body.style.overflow = '';
}

closeDailyPromptModal.addEventListener('click', closePrompt);

dailyPromptModal.addEventListener('click', (e) => {
    if (e.target === dailyPromptModal) {
        closePrompt();
    }
});

/* Date */
currentDateElement.textContent = new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric'
});

/* Load prompt */
async function loadPrompt() {
    try {
        const data = await fetchJson('Backend/get_daily_prompt.php');

        currentPromptId = data.prompt.prompt_id;
        promptQuestionElement.textContent = data.prompt.prompt_text;

        if (data.already_answered_today && data.today_answer) {
            promptAnswerElement.value = data.today_answer.answer;
            alreadyAnsweredToday = true;
        
                submitPromptButton.textContent = 'Update Answer';
        } else {
                submitPromptButton.textContent = 'Save Answer';
            }

    } catch (e) {
        promptQuestionElement.textContent = "Couldn't load prompt";
    }
}

/* Weekly answers */
async function loadWeekly() {
    try {
        const data = await fetchJson('Backend/get_weekly_prompt_answers.php');

        const answers = data.answers || [];
        completedCountElement.textContent = answers.length;

        weeklyReflectionsElement.innerHTML = answers.map(a => `
            <div class="diary-entry">
                <div class="diary-date">${escapeHtml(a.date_string)}</div>
                <div>${escapeHtml(a.answer)}</div>
            </div>
        `).join('');

    } catch (e) {
        console.error(e);
    }
}

/* Submit prompt */
submitPromptButton.addEventListener('click', async function () {

    const answer = promptAnswerElement.value.trim();

    if (!answer) {
        showToast('Write something first', 'error');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('prompt_id', currentPromptId);
        formData.append('answer', answer);

       const data = await fetchJson('Backend/save_daily_prompt_answer.php', {
    method: 'POST',
    body: formData
});

alreadyAnsweredToday = true;

// 🔥 change button dynamically
submitPromptButton.textContent = 'Update Answer';

// ✅ show correct message (saved OR updated)
showToast(data.message || 'Saved!');

// reload weekly
await loadWeekly();

// ✅ auto close after short delay
setTimeout(() => {
    closePrompt();
}, 1200);

    } catch (e) {
        showToast(e.message, 'error');
    }
});

/* Skip */
skipPromptButton.addEventListener('click', async function () {

    //if (!confirm("Skip today?")) return;

    try {
        const formData = new FormData();
        formData.append('prompt_id', currentPromptId);

            const data = await fetchJson('Backend/skip_daily_prompt.php', {
            method: 'POST',
            body: formData
        });

        promptAnswerElement.value = '';
        alreadyAnsweredToday = false;
        submitPromptButton.textContent = 'Save Answer';

        showToast(data.message || 'Removed!');

        await loadWeekly();

        setTimeout(() => {
            closePrompt();
        }, 1200);

    } catch (e) {
        showToast(e.message, 'error');
    }
});

/* =========================
   INIT
========================= */

window.addEventListener('load', async function () {

    loadReceivedMessages();
    loadPrompt();
    loadWeekly();

    if (typeof SHOW_DAILY_PROMPT_ON_LOAD !== 'undefined' && SHOW_DAILY_PROMPT_ON_LOAD) {
        setTimeout(openPrompt, 500);
    }
});