<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
                <li><a href="daily-prompt.php">Prompts</a></li>
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
                Check in with yourself and, if you want, send a kind anonymous message to someone who may need it.
            </p>
        </div>

        <div class="card" style="margin-bottom: 2rem;">

            <div class="form-group">
                <label style="font-size: 1.125rem;" id="messageLabel">
                    Optional anonymous message
                </label>
                <textarea
                    class="form-control"
                    id="feelingText"
                    placeholder="Write a kind anonymous message for someone feeling the same emotion..."
                    rows="4"
                ></textarea>
            </div>

            <div>
                <label style="font-size: 1.125rem; display: block; margin-bottom: 1rem;">
                    How are you feeling?
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

            <div id="messageOption" style="display: none; margin-top: 2rem; padding: 1.5rem; background: rgba(124, 58, 237, 0.05); border-radius: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <input type="checkbox" id="sendAnonymousMsg" style="width: 20px; height: 20px; cursor: pointer;">
                    <label for="sendAnonymousMsg" style="cursor: pointer; font-size: 1.125rem; margin: 0;">
                        Send this message anonymously to someone feeling the same emotion
                    </label>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">
                    Your message can brighten someone's day and help them feel less alone.
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

        <div class="card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <div>
                    <h2 style="font-size: 1.6rem; margin-bottom: 0.35rem; font-weight: 600;">
                        Messages You’ve Received
                    </h2>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Anonymous support from people who shared your emotion.
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
                        When someone sends you anonymous support, it will appear here.
                    </p>
                </div>
            </div>
        </div>

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

    <script src="js/dashboard.js?v=5"></script>
</body>
</html>