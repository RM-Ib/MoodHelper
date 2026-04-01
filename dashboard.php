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
        <a href="dashboard.html" class="logo">
            <span class="logo-icon">❤️</span>
            <span class="logo-text">MoodHelper</span>
        </a>
        <ul class="nav-links">
            <li><a href="dashboard.html" class="active">Dashboard</a></li>
            <li><a href="diary.html" class="">Diary</a></li>
            <li><a href="daily-prompt.html" class="">Prompts</a></li>
            <li><a href="reflection-board.php" >Reflection Board</a></li>
            <li><a href="groups.html" class="">Groups</a></li>
            <li><a href="mood-support.html" class="">Support</a></li>
            <li><a href="settings.html" class="">Settings</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="account.php" class="btn btn-secondary">Account</a>
            <a href="index.html" class="btn btn-primary">Logout</a>
        </div>
    </div>
</nav>

    <div class="container" style="padding: 3rem 2rem;">
      
        <div style="margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                How are you feeling today?
            </h1>
            <p style="font-size: 1.125rem; color: var(--text-secondary);">
                Take a moment to check in with yourself
            </p>
        </div>

       
        <div class="card" style="margin-bottom: 2rem;">
            <div class="form-group">
                <label style="font-size: 1.125rem;">Tell us about your day (optional)</label>
                <textarea 
                    class="form-control" 
                    id="feelingText"
                    placeholder="What's on your mind? You're in a safe space..."
                    rows="4"
                ></textarea>
            </div>

           
            <div>
                <label style="font-size: 1.125rem; display: block; margin-bottom: 1rem;">
                    How are you feeling?
                </label>
                <div class="emotion-grid">
                    <button class="emotion-btn" data-emotion="happy">
                        <span class="emotion-emoji">😊</span>
                        <span>Happy</span>
                    </button>
                    <button class="emotion-btn" data-emotion="sad">
                        <span class="emotion-emoji">😢</span>
                        <span>Sad</span>
                    </button>
                    <button class="emotion-btn" data-emotion="anxious">
                        <span class="emotion-emoji">😰</span>
                        <span>Anxious</span>
                    </button>
                    <button class="emotion-btn" data-emotion="angry">
                        <span class="emotion-emoji">😠</span>
                        <span>Angry</span>
                    </button>
                    <button class="emotion-btn" data-emotion="neutral">
                        <span class="emotion-emoji">😐</span>
                        <span>Neutral</span>
                    </button>
                    <button class="emotion-btn" data-emotion="disappointed">
                        <span class="emotion-emoji">😞</span>
                        <span>Disappointed</span>
                    </button>
                </div>
            </div>

          
            <div style="margin-top: 2rem;">
                <button class="btn btn-primary btn-large" id="submitFeeling" style="width: 100%;">
                    Submit
                </button>
            </div>

            <div id="messageOption" style="display: none; margin-top: 2rem; padding: 1.5rem; background: rgba(124, 58, 237, 0.05); border-radius: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <input type="checkbox" id="sendAnonymousMsg" style="width: 20px; height: 20px; cursor: pointer;">
                    <label for="sendAnonymousMsg" style="cursor: pointer; font-size: 1.125rem; margin: 0;">
                        Would you like to send an anonymous message to someone feeling a similar emotion?
                    </label>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">
                    Your message will brighten someone's day and help them feel less alone
                </p>
            </div>
        </div>

        <div>
            <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;">Quick Access</h2>
            <div class="features">
                <a href="mood-tracking.html" class="feature-card" style="text-decoration: none; color: inherit;">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white;">
                        📊
                    </div>
                    <h3>Mood Tracking</h3>
                    <p>View your emotional journey</p>
                </a>

                <a href="diary.html" class="feature-card" style="text-decoration: none; color: inherit;">
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

                <a href="groups.html" class="feature-card" style="text-decoration: none; color: inherit;">
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

    <script src="js/dashboard.js"></script>
</body>
</html>
