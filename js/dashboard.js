

let selectedEmotion = null;

const emotionButtons = document.querySelectorAll('.emotion-btn');
const messageOption = document.getElementById('messageOption');
const feelingText = document.getElementById('feelingText');
const sendAnonymousMsg = document.getElementById('sendAnonymousMsg');
const submitFeelingButton = document.getElementById('submitFeeling');
const successMessage = document.getElementById('successMessage');
const receivedMessagesContainer = document.getElementById('receivedMessages');

// Emotion button handling
emotionButtons.forEach(button => {
    button.addEventListener('click', function () {
        emotionButtons.forEach(btn => btn.classList.remove('selected'));

        this.classList.add('selected');
        selectedEmotion = this.getAttribute('data-emotion');

        messageOption.style.display = 'block';

        setTimeout(() => {
            messageOption.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }, 100);
    });
});

// Submit feeling
submitFeelingButton.addEventListener('click', async function () {
    const messageText = feelingText.value.trim();
    const wantsToSendMessage = sendAnonymousMsg.checked;

    if (!selectedEmotion) {
        showToast('Please select how you are feeling.', 'error');
        return;
    }

    if (wantsToSendMessage && !messageText) {
        showToast('Please write a message if you want to send one anonymously.', 'error');
        successMessage.style.display = 'none';
        return;
    }

    submitFeelingButton.disabled = true;
    const originalText = submitFeelingButton.textContent;
    submitFeelingButton.textContent = 'Submitting...';

    try {
        const formData = new FormData();
        formData.append('mood', selectedEmotion);
        formData.append('notes', messageText);
        formData.append('send_message', wantsToSendMessage ? '1' : '0');

        const response = await fetch('Backend/save_dashboard_checkin.php', {
            method: 'POST',
            body: formData
        });

        const rawText = await response.text();
        console.log('save_dashboard_checkin raw response:', rawText);

        let data;
        try {
            data = JSON.parse(rawText);
        } catch (e) {
            throw new Error('Invalid server response: ' + rawText);
        }

        if (data.status !== 'success') {
            throw new Error(data.message || 'Something went wrong');
        }

successMessage.style.display = 'block';
showToast(data.message || 'Mood check-in saved successfully.');

// 🔥 redirect if negative mood
const negativeMoods = ['sad', 'anxious', 'angry', 'disappointed'];

if (negativeMoods.includes(selectedEmotion)) {
    showToast('We’re here to support you. Redirecting you to chat 💜');
    setTimeout(() => {
        window.location.href = 'mood-support.php';
    }, 1200); // small delay so user sees success first
}

        feelingText.value = '';
        sendAnonymousMsg.checked = false;
        emotionButtons.forEach(btn => btn.classList.remove('selected'));
        messageOption.style.display = 'none';
        selectedEmotion = null;

        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);

        await loadReceivedMessages();
    } catch (error) {
        console.error(error);
        showToast(error.message || 'Failed to save check-in.', 'error');
        successMessage.style.display = 'none';
    } finally {
        submitFeelingButton.disabled = false;
        submitFeelingButton.textContent = originalText;
    }
});
async function loadReceivedMessages() {
    if (!receivedMessagesContainer) return;

    try {
        const response = await fetch('Backend/get_received_messages.php');
        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Could not load messages');
        }

        const messages = data.messages || [];
        const unreadCount = data.unread_count || 0;

        if (unreadCount > 0) {
            showToast(
                unreadCount === 1
                    ? "You received a new anonymous message 💌"
                    : `You received ${unreadCount} new anonymous messages 💌`
            );
        }

        if (messages.length === 0) {
            receivedMessagesContainer.innerHTML = `
                <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-secondary);">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">💌</div>
                    <p style="margin: 0; font-size: 1rem;">No messages yet.</p>
                    <p style="margin-top: 0.5rem; font-size: 0.9rem;">
                        When someone sends you anonymous support, it will appear here.
                    </p>
                </div>
            `;
            return;
        }

        receivedMessagesContainer.innerHTML = messages.map(message => `
            <div class="card" style="margin-bottom: 1rem; padding: 1.25rem; border: 1px solid rgba(124, 58, 237, 0.12);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="background: rgba(124, 58, 237, 0.08); color: var(--primary-purple); padding: 0.35rem 0.7rem; border-radius: 999px; font-size: 0.85rem; font-weight: 500; text-transform: capitalize;">
                        ${escapeHtml(message.mood)}
                    </span>
                    <span style="font-size: 0.85rem; color: var(--text-secondary);">
                        ${escapeHtml(message.created_at)}
                    </span>
                </div>
                <p style="margin: 0; line-height: 1.7; color: var(--text-primary);">
                    ${escapeHtml(message.message_text).replace(/\n/g, '<br>')}
                </p>
            </div>
        `).join('');
    } catch (error) {
        console.error(error);
        receivedMessagesContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #b91c1c;">
                Failed to load messages.
            </div>
        `;
    }
}
function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

// Toast notification helper
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

function setGreeting() {
    const hour = new Date().getHours();
    let greeting = 'Good day';

    if (hour < 12) greeting = 'Good morning';
    else if (hour < 18) greeting = 'Good afternoon';
    else greeting = 'Good evening';

    console.log(greeting);
}

setGreeting();
loadReceivedMessages();