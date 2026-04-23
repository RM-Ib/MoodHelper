
const currentDateElement = document.getElementById('currentDate');
const promptQuestionElement = document.getElementById('promptQuestion');
const promptAnswerElement = document.getElementById('promptAnswer');
const submitPromptButton = document.getElementById('submitPrompt');
const skipPromptButton = document.getElementById('skipPrompt');
const successMessageElement = document.getElementById('successMessage');
const weeklyReflectionsElement = document.getElementById('weeklyReflections');
const completedCountElement = document.getElementById('completedCount');

let currentPromptId = null;
let alreadyAnsweredToday = false;

currentDateElement.textContent = new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
}

function showInlineMessage(message, isError = false) {
    successMessageElement.style.display = 'block';
    successMessageElement.style.background = isError
        ? 'rgba(239, 68, 68, 0.08)'
        : 'rgba(5, 150, 105, 0.08)';
    successMessageElement.style.borderLeft = isError
        ? '3px solid #ef4444'
        : '3px solid #059669';

    successMessageElement.innerHTML = `
        <p style="color: ${isError ? '#991b1b' : '#065f46'}; font-weight: 500; margin: 0;">
            ${escapeHtml(message)}
        </p>
    `;

    setTimeout(() => {
        successMessageElement.style.display = 'none';
    }, 5000);
}

async function fetchJson(url, options = {}) {
    const response = await fetch(url, options);
    const data = await response.json();

    if (!response.ok || data.status !== 'success') {
        throw new Error(data.message || 'Something went wrong');
    }

    return data;
}

async function loadTodayPrompt() {
    try {
        const data = await fetchJson('Backend/get_daily_prompt.php');
        currentPromptId = data.prompt.prompt_id;
        alreadyAnsweredToday = Boolean(data.already_answered_today);
        promptQuestionElement.textContent = data.prompt.prompt_text;

        if (alreadyAnsweredToday && data.today_answer?.answer) {
            promptAnswerElement.value = data.today_answer.answer;
            submitPromptButton.textContent = 'Update Answer';
        }
    } catch (error) {
        promptQuestionElement.textContent = 'Unable to load today\'s prompt.';
        showInlineMessage(error.message, true);
    }
}

async function loadWeeklyReflections() {
    try {
        const data = await fetchJson('Backend/get_weekly_prompt_answers.php');
        const weeklyAnswers = data.answers || [];

        completedCountElement.textContent = weeklyAnswers.length;

        if (weeklyAnswers.length === 0) {
            weeklyReflectionsElement.innerHTML = `
                <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📝</div>
                    <p>No reflections yet this week. Start today!</p>
                </div>
            `;
            return;
        }

        weeklyReflectionsElement.innerHTML = weeklyAnswers.map(reflection => `
            <div class="diary-entry fade-in">
                <div class="diary-date">${escapeHtml(reflection.date_string)}</div>
                <h4 style="color: var(--primary-purple); margin-bottom: 0.5rem; font-size: 1rem;">
                    ${escapeHtml(reflection.prompt_text)}
                </h4>
                <div class="diary-content">${escapeHtml(reflection.answer).replace(/\n/g, '<br>')}</div>
            </div>
        `).join('');
    } catch (error) {
        weeklyReflectionsElement.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #b91c1c;">
                ${escapeHtml(error.message)}
            </div>
        `;
    }
}

submitPromptButton.addEventListener('click', async function () {
    const answer = promptAnswerElement.value.trim();

    if (!answer) {
        showInlineMessage('Please write your reflection before submitting.', true);
        return;
    }

    if (!currentPromptId) {
        showInlineMessage('The daily prompt is still loading. Please try again.', true);
        return;
    }

    submitPromptButton.disabled = true;
    submitPromptButton.textContent = alreadyAnsweredToday ? 'Updating...' : 'Saving...';

    try {
        const formData = new FormData();
        formData.append('prompt_id', currentPromptId);
        formData.append('answer', answer);

        const data = await fetchJson('Backend/save_daily_prompt_answer.php', {
            method: 'POST',
            body: formData
        });

        alreadyAnsweredToday = true;
        submitPromptButton.textContent = 'Update Answer';
        showInlineMessage(data.message || 'Your answer has been saved!');
        await loadWeeklyReflections();

        setTimeout(() => {
            weeklyReflectionsElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 300);
    } catch (error) {
        submitPromptButton.textContent = alreadyAnsweredToday ? 'Update Answer' : 'Save Answer';
        showInlineMessage(error.message, true);
    } finally {
        submitPromptButton.disabled = false;
    }
});

skipPromptButton.addEventListener('click', async function () {
    if (!confirm('Are you sure you want to skip today\'s reflection?')) {
        return;
    }

    if (!currentPromptId) {
        showInlineMessage('The daily prompt is still loading. Please try again.', true);
        return;
    }

    skipPromptButton.disabled = true;
    const originalText = skipPromptButton.textContent;
    skipPromptButton.textContent = 'Skipping...';

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

        showInlineMessage(data.message || 'Today was skipped.');
        await loadWeeklyReflections();
    } catch (error) {
        showInlineMessage(error.message, true);
    } finally {
        skipPromptButton.disabled = false;
        skipPromptButton.textContent = originalText;
    }
});

loadTodayPrompt();
loadWeeklyReflections();
