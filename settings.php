<!-- By Antonio Karam -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Moodhelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="../Images/home/cart3.svg" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
<div class="container">
<a href="dashboard.html" class="logo">
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
<a href="index.html" class="btn btn-primary">Logout</a>
</div>
</div>
</nav>

    <main class="main-content">
        <div class="profile-container">
            <h1>Settings</h1>

            <form class="profile-form" onsubmit="return validateSettingsForm()">
                
                <div class="form-group">
                    <label for="theme">Theme</label>
                    <select id="theme" class="form-control">
                        <option value="light">Light</option>
                        <option value="dark" selected>Dark</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.html'">Cancel</button>

            </form>
        </div>
    </main>

    <script>
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        const navbar = document.getElementById('navbar');

        mobileNavToggle.addEventListener('click', () => {
            const visibility = navbar.getAttribute('data-visible');
            if (visibility === "false") {
                navbar.setAttribute('data-visible', "true");
                mobileNavToggle.setAttribute('aria-expanded', "true");
            } else {
                navbar.setAttribute('data-visible', "false");
                mobileNavToggle.setAttribute('aria-expanded', "false");
            }
        });

        function validateSettingsForm() {
            alert("Settings saved successfully!");
            return false; // prevent actual submission for now
        }
    </script>

</body>
</html>