<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$showDailyPrompt = false;
if (!empty($_SESSION['show_daily_prompt'])) {
    $showDailyPrompt = true;
    unset($_SESSION['show_daily_prompt']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoodHelper</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">❤️</span>
                <span class="logo-text">MoodHelper</span>
            </a>

            <ul class="nav-links">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="diary.php">Diary</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="reflection-board.php">Reflection Board</a></li>
                <li><a href="groups.php">Groups</a></li>
                <li><a href="mood-support.php">Support</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>

            <div class="nav-buttons">
                <a href="account.php" class="btn btn-secondary">Account</a>
                <a href="Backend/logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="padding: 3rem 2rem;">

        <div style="margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                How are you feeling today?
            </h1>
            <p style="font-size: 1.125rem; color: var(--text-secondary);">
                Check in with yourself, save an optional note, and support someone who may be feeling the same way.
            </p>
        </div>

        <!-- Mood Check-in Card -->
        <div class="card" style="margin-bottom: 2rem;">

            <div>
                <label style="font-size: 1.125rem; display: block; margin-bottom: 1rem;">
                    Choose your mood <span style="color:#dc2626;">*</span>
                </label>

                <div class="emotion-grid">
                    <button class="emotion-btn" data-emotion="happy" type="button">
                        <span class="emotion-emoji">😊</span>
                        <span>Happy</span>
                    </button>

                    <button class="emotion-btn" data-emotion="sad" type="button">
                        <span class="emotion-emoji">😢</span>
                        <span>Sad</span>
                    </button>

                    <button class="emotion-btn" data-emotion="anxious" type="button">
                        <span class="emotion-emoji">😰</span>
                        <span>Anxious</span>
                    </button>

                    <button class="emotion-btn" data-emotion="angry" type="button">
                        <span class="emotion-emoji">😠</span>
                        <span>Angry</span>
                    </button>

                    <button class="emotion-btn" data-emotion="neutral" type="button">
                        <span class="emotion-emoji">😐</span>
                        <span>Neutral</span>
                    </button>

                    <button class="emotion-btn" data-emotion="disappointed" type="button">
                        <span class="emotion-emoji">😞</span>
                        <span>Disappointed</span>
                    </button>

                    <button class="emotion-btn" data-emotion="calm" type="button">
                        <span class="emotion-emoji">😌</span>
                        <span>Calm</span>
                    </button>

                    <button class="emotion-btn" data-emotion="grateful" type="button">
                        <span class="emotion-emoji">🙏</span>
                        <span>Grateful</span>
                    </button>
                </div>
            </div>

            <!-- Optional personal note -->
            <div style="margin-top: 2rem;">
                <label style="font-size: 1.05rem; margin-bottom: 0.75rem; display: block;">
                    Optional note
                </label>
                <textarea
                    class="form-control"
                    id="moodNote"
                    placeholder="Write an optional note about how you're feeling..."
                    rows="4"
                ></textarea>
            </div>

            <!-- Checkbox + peer message -->
            <div id="messageOption" style="display: none; margin-top: 2rem; padding: 1.5rem; background: rgba(124, 58, 237, 0.05); border-radius: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <input type="checkbox" id="sendAnonymousMsg" style="width: 20px; height: 20px; cursor: pointer;">
                    <label for="sendAnonymousMsg" style="cursor: pointer; font-size: 1.05rem; margin: 0;">
                        Send a supportive message to another user who may be feeling the same way
                    </label>
                </div>

                <div id="messageTextWrapper" style="display: none; margin-top: 1rem;">
                    <label style="font-size: 1.05rem; margin-bottom: 0.75rem; display: block;">
                        Your message
                    </label>
                    <textarea
                        class="form-control"
                        id="peerMessage"
                        placeholder="Write a kind and supportive message..."
                        rows="4"
                    ></textarea>
                </div>

                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">
                    Your message will be shared anonymously.
                </p>
            </div>

            <div style="margin-top: 2rem;">
                <button type="button" class="btn btn-primary btn-large" id="submitFeeling" style="width: 100%;">
                    Submit
                </button>
            </div>

            <div id="successMessage" style="display: none; margin-top: 2rem; padding: 1.5rem; background: rgba(5, 150, 105, 0.08); border-left: 3px solid #059669; border-radius: 8px;">
                <p style="color: #065f46; font-weight: 500; margin: 0;">
                    ✓ Your check-in has been submitted successfully.
                </p>
            </div>
        </div>

        <!-- Received Messages -->
        <div class="card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <div>
                    <h2 style="font-size: 1.6rem; margin-bottom: 0.35rem; font-weight: 600;">
                        Messages You’ve Received
                    </h2>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Support from people who shared your emotion.
                    </p>
                </div>

                <span style="background: rgba(124, 58, 237, 0.08); color: var(--primary-purple); padding: 0.45rem 0.85rem; border-radius: 999px; font-size: 0.9rem; font-weight: 500;">
                    Inbox
                </span>
            </div>

            <div id="receivedMessages">
                <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-secondary);">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">💌</div>
                    <p style="margin: 0; font-size: 1rem;">
                        No messages yet.
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 0.9rem;">
                        When someone sends you support, it will appear here.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div>
            <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;">Quick Access</h2>

            <div class="features">
                <a href="mood-tracking.php" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white;">
                        📊
                    </div>
                    <h3>Mood Tracking</h3>
                    <p>View your emotional journey</p>
                </a>

                <a href="diary.php" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white;">
                        📖
                    </div>
                    <h3>Private Diary</h3>
                    <p>Write your thoughts</p>
                </a>

                <a href="reflection-board.php" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8); color: white;">
                        💬
                    </div>
                    <h3>Reflection Board</h3>
                    <p>Share anonymously</p>
                </a>

                <a href="groups.php" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6); color: white;">
                        👥
                    </div>
                    <h3>Support Groups</h3>
                    <p>Find your community</p>
                </a>

                <a href="articles.html" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #14b8a6, #5eead4); color: white;">
                        📄
                    </div>
                    <h3>Articles</h3>
                    <p>Learn and grow</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Daily Prompt Popup -->
    <div id="dailyPromptModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:white; width:100%; max-width:800px; max-height:90vh; overflow-y:auto; border-radius:1.5rem; padding:2rem; position:relative; box-shadow:0 25px 50px rgba(0,0,0,0.18);">
            <button id="closeDailyPromptModal" type="button" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:1.75rem; cursor:pointer; color:#6b7280;">&times;</button>

            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(139, 92, 246, 0.08); color: #8b5cf6; padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1.5rem;">
                    <span>✨</span>
                    <span>Daily Check-In</span>
                </div>

                <h1 style="font-size: 2rem; margin-bottom: 1rem; font-weight: 600;">
                    Today's Question
                </h1>

                <p style="color: var(--text-secondary); font-size: 1.125rem;" id="currentDate"></p>
            </div>

            <div style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.05), rgba(59, 130, 246, 0.05)); padding: 2rem; border-radius: 1rem; margin-bottom: 2rem; text-align: center;">
                <h2 style="font-size: 1.5rem; color: var(--primary-purple); margin-bottom: 1rem;" id="promptQuestion"></h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">
                    Take your time. There’s no right or wrong answer.
                </p>
            </div>

            <div class="form-group">
                <label style="font-size: 1.05rem;">Your Answer</label>
                <textarea 
                    class="form-control" 
                    id="promptAnswer"
                    placeholder="Write your thoughts here..."
                    rows="8"
                ></textarea>
            </div>

            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <button class="btn btn-primary" id="submitPrompt" style="flex: 1;">
                    Save Answer
                </button>
                <button class="btn btn-secondary" id="skipPrompt" style="flex: 1;">
                    Skip Today
                </button>
            </div>

            <div id="promptSuccessMessage" style="display: none; margin-top: 2rem; padding: 1.5rem; background: rgba(5, 150, 105, 0.08); border-left: 3px solid #059669; border-radius: 8px;">
                <p style="color: #065f46; font-weight: 500; margin: 0;">
                    ✓ Your answer has been saved.
                </p>
            </div>

            <div style="margin-top: 3rem;">
                <h2 style="font-size: 1.65rem; margin-bottom: 1.5rem; font-weight: 600;">
                    This Week's Answers
                </h2>
                <div id="weeklyReflections" class="diary-entries"></div>
            </div>

            <div style="margin-top: 3rem; text-align: center; padding: 2rem; background: linear-gradient(to right, #8b5cf6, #6366f1); border-radius: 14px; color: white;">
                <h3 style="font-size: 1.4rem; margin-bottom: 1rem; font-weight: 600;">
                    Keep Going! 🌟
                </h3>
                <p style="font-size: 1.05rem; opacity: 0.92;">
                    You've answered <strong id="completedCount">0</strong> prompts this week.
                    Each answer is a step toward better self-awareness.
                </p>
            </div>
        </div>
    </div>

    <script>
        const SHOW_DAILY_PROMPT_ON_LOAD = <?php echo $showDailyPrompt ? 'true' : 'false'; ?>;
    </script>
    <script src="js/dashboard.js?v=8"></script>
</body>
</html>