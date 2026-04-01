<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "moodhelperdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // Find user
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, login_streak, last_login FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $username, $password_hash, $login_streak, $last_login);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {

            // --------------------------
            // LOGIN STREAK CALCULATION
            // --------------------------
            $today = new DateTime("today");

            if (empty($last_login) || $last_login == '0000-00-00 00:00:00') {
                // First login ever
                $login_streak = 1;
            } else {
                $lastLoginDate = new DateTime($last_login);
                $diff = $today->diff($lastLoginDate)->days;

                if ($diff === 1) {
                    // Consecutive day
                    $login_streak += 1;
                } elseif ($diff > 1) {
                    // Missed days, reset streak
                    $login_streak = 1;
                }
                // if $diff === 0, same day login, do nothing
            }

            // Update last_login and login_streak in DB
            $update = $conn->prepare("UPDATE users SET last_login = NOW(), login_streak = ? WHERE user_id = ?");
            $update->bind_param("ii", $login_streak, $user_id);
            $update->execute();
            $update->close();

            // Store session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MoodHelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Link to your main stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="container" style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
        
        <div class="card fade-in" style="max-width: 450px; width:100%;">
            
            <div class="text-center mb-3">
                <img src="Images/LogoForLogin.png" 
                     alt="Logo" 
                     style="width:120px; margin-bottom:1rem;">
                
                <h2 class="gradient-text">Welcome Back</h2>
                <p class="mb-2" style="color: var(--text-secondary);">
                    Log in to continue
                </p>
            </div>

            <?php if (!empty($error)) : ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="login.php" onsubmit="return validateForm()">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           required>
                </div>

                <div class="text-center mb-3">
                    <a href="ForgotPassword.html" 
                       style="font-size: 0.9rem; color: var(--primary-purple);">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary btn-large" style="width:100%;">
                    Log In
                </button>

            </form>

            <div class="text-center mt-4">
                <p class="mb-1">Don't have an account?</p>
                <a href="signup.php" 
                   style="color: var(--primary-purple); font-weight: 600;">
                    Sign Up
                </a>
            </div>

        </div>
    </div>


    <script>
    function validateForm() {
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        if (!email || !password) {
            alert("Both fields are required.");
            return false;
        }

        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>