<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "moodhelperdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = trim($_POST['Fname']);
    $lname = trim($_POST['Lname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($fname) || empty($lname) || empty($username) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Username or email already taken.");
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $username, $email, $passwordHash);

    if ($stmt->execute()) {
    header("Location: login.php");
    exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - MoodHelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="auth-wrapper">
        
        <div class="card fade-in" style="max-width: 500px; width:100%;">
            
            <div class="text-center mb-3">
                <h2 class="gradient-text">Create Your Account</h2>
                <p class="mb-2" style="color: var(--text-secondary);">
                    Join us and get started
                </p>
            </div>

            <form method="POST" action="signup.php" onsubmit="return validateForm()">

                <div class="form-group">
                    <label for="Fname">First Name</label>
                    <input type="text" id="Fname" name="Fname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="Lname">Last Name</label>
                    <input type="text" id="Lname" name="Lname" class="form-control" required>
                </div>


                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>


                <button type="submit" class="btn btn-primary btn-large" style="width:100%;">
                    Sign Up
                </button>

            </form>

            <div class="text-center mt-4">
                <p class="mb-1">Already have an account?</p>
                <a href="login.php" style="color: var(--primary-purple); font-weight:600;">
                    Log In
                </a>
            </div>

        </div>
    </div>


    <script>
        function validateForm() {
            const fname = document.getElementById("Fname").value.trim();
            const lname = document.getElementById("Lname").value.trim();
            const email = document.getElementById("email").value.trim();
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value;

            if (!fname || !lname || !username || !email || !password) {
                alert("All required fields must be filled.");
                return false;
            }

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            const usernamePattern = /^[a-zA-Z0-9_]{3,20}$/;

            if (!usernamePattern.test(username)) {
                alert("Username must be 3-20 characters and contain only letters, numbers, or underscores.");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters.");
                return false;
            }

            return true; 
        }
    </script>

</body>
</html>