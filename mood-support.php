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
    <title>Support Chat - MoodHelper</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
 
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">❤️</span>
                <span class="logo-text">MoodHelper</span>
            </a>

            <input type="checkbox" id="nav-menu-toggle" class="nav-menu-toggle">

            <label for="nav-menu-toggle" class="hamburger" aria-label="Open navigation menu">
                <span></span>
                <span></span>
                <span></span>
            </label>
            
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="diary.php">Diary</a></li>
                <li><a href="reflection-board.php">Reflection Board</a></li>
                <li><a href="groups.php">Groups</a></li>
                <li><a href="mood-support.php" class="active">Support</a></li>
            </ul>

            <div class="nav-buttons">
                <a href="account.php" class="btn btn-secondary">Account</a>
                <a href="index.html" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="padding: 3rem 2rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                Support Chat
            </h1>
            <p style="font-size: 1.125rem; color: var(--text-secondary);" id="emotionMessage">
                A calm place to talk through what you're feeling.
            </p>
        </div>

        <div class="chat-container" style="position: relative;">
            
            <button id="expandChatBtn" style="
                position:absolute;
                top:10px;
                right:10px;
                background:none;
                border:none;
                font-size:1.1rem;
                cursor:pointer;
                color:#9ca3af;
                z-index:100000;
            ">
            </button>

            <div class="chat-messages" id="chatMessages">
                <div class="message ai fade-in">
                    <div class="message-header">MoodHelper Support</div>
                    <div>I’m here to help you slow things down and think through what you’re feeling. What’s going on right now?</div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: flex-end;">
                <textarea 
                    class="form-control" 
                    id="userMessage"
                    rows="1"
                    style="
                        flex:1;
                        resize:none;
                        overflow:hidden;
                        min-height:40px;
                        max-height:120px;
                    "
                ></textarea>

                <button class="btn btn-primary" id="sendMessage" type="button">
                    Send
                </button>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem; text-align: center;">
                Helpful Tools
            </h2>

            <div class="recommendations" id="recommendations">
                <div class="recommendation-card">
                    <div class="recommendation-icon">🧘</div>
                    <h3>Breathing Exercise</h3>
                    <p>A simple grounding exercise for stress and overwhelm.</p>
                    <button class="btn btn-primary btn-small" onclick="startBreathingExercise()">
                        Start
                    </button>
                </div>

                <div class="recommendation-card">
                    <div class="recommendation-icon">✍️</div>
                    <h3>Go to Diary</h3>
                    <p>Write things out privately if that feels easier.</p>
                    <button class="btn btn-primary btn-small" onclick="goToDiary()">
                        Open Diary
                    </button>
                </div>

                <div class="recommendation-card">
                    <div class="recommendation-icon">💌</div>
                    <h3>Anonymous Support</h3>
                    <p>Check in and connect with someone feeling something similar.</p>
                    <button class="btn btn-primary btn-small" onclick="goToDashboard()">
                        Go There
                    </button>
                </div>
            </div>
        </div>

        <div id="breathingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; padding: 3rem; border-radius: 2rem; text-align: center; max-width: 500px; margin: 2rem;">
                <h2 style="margin-bottom: 2rem;">Breathing Exercise</h2>

                <div id="breathingCircle" style="width: 200px; height: 200px; margin: 2rem auto; border-radius: 50%; background: linear-gradient(135deg, #7c3aed, #3b82f6); transition: transform 4s ease-in-out;"></div>

                <p id="breathingText" style="font-size: 1.5rem; margin-bottom: 2rem;">Breathe in...</p>

                <button class="btn btn-secondary" onclick="closeBreathingExercise()">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="js/mood-support.js?v=2"></script>
</body>
</html>