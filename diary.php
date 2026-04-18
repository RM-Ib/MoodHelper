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
    <title>Private Diary - MoodHelper</title>
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
                📖 My Private Diary
            </h1>
            <p style="font-size: 1.125rem; color: var(--text-secondary);">
                Your personal space to journal thoughts and feelings. Stored privately and shown only in your account.
            </p>
        </div>

        <div style="background: rgba(124, 58, 237, 0.05); border-left: 4px solid var(--primary-purple); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 3rem;">
            <p style="margin: 0; color: var(--text-primary);">
                🔒 <strong>Your Privacy Matters:</strong> Diary entries are encrypted before they are stored in the database and are only displayed inside your account.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 3rem; max-width: 1000px; margin: 0 auto;">
       
            <div class="card">
                <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;" id="entryFormHeading">
                    ✍️ New Entry
                </h2>
                
                <div class="form-group">
                    <label>Title (Optional)</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="entryTitle"
                        placeholder="Give your entry a title..."
                    >
                </div>

                <div class="form-group">
                    <label>What's on your mind?</label>
                    <textarea 
                        class="form-control" 
                        id="entryContent"
                        placeholder="Write freely... This is your space to express yourself."
                        rows="10"
                    ></textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" class="btn btn-primary btn-large" id="saveEntry" style="flex: 1;">
                        Save Entry
                    </button>
                    <button type="button" class="btn btn-secondary" id="clearEntry">
                        Clear
                    </button>
                </div>

                <div id="successMsg" style="display: none; margin-top: 1.5rem; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; border-radius: 0.5rem;">
                    <p style="color: #065f46; font-weight: 500; margin: 0;">
                        ✓ Entry saved successfully!
                    </p>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.75rem; margin: 0;">
                        Past Entries
                    </h2>
                    <select class="form-control" id="filterEntries" style="width: auto;">
                        <option value="all">All Entries</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>

                <div id="diaryEntries" class="diary-entries"></div>

                <div id="emptyState" style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">No entries yet</h3>
                    <p>Start journaling to see your entries here</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 4rem; background: linear-gradient(135deg, #7c3aed, #3b82f6); border-radius: 2rem; padding: 3rem; text-align: center; color: white;">
            <h3 style="font-size: 1.75rem; margin-bottom: 1.5rem;">
                Your Journaling Journey
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2rem;">
                <div>
                    <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;" id="totalEntries">0</div>
                    <div style="opacity: 0.9;">Total Entries</div>
                </div>
                <div>
                    <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;" id="currentStreak">0</div>
                    <div style="opacity: 0.9;">Day Streak</div>
                </div>
                <div>
                    <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;" id="thisMonth">0</div>
                    <div style="opacity: 0.9;">This Month</div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/diary.js?v=6"></script>
</body>
</html>