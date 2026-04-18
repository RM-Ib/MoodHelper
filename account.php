<?php
session_start();

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// DB connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "moodhelperdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Handle form submission (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = trim($_POST['Fname']);
    $lname = trim($_POST['Lname']);
    $username = trim($_POST['username']);

    if (empty($fname) || empty($lname) || empty($username)) {
        $error = "All fields are required.";
    } else {

        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=? WHERE user_id=?");
        $stmt->bind_param("sssi", $fname, $lname, $username, $user_id);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username; // update session
            $success = "Profile updated successfully.";
        } else {
            $error = "Error updating profile.";
        }

        $stmt->close();
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, username, email FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $username, $email);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - Moodhelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../Images/home/cart3.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;600;700&display=swap" rel="stylesheet">

    <!-- External CSS -->
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="diary.php" class="">Diary</a></li>
            <li><a href="daily-prompt.php" class="">Prompts</a></li>
            <li><a href="groups.php" class="">Groups</a></li>
            <li><a href="mood-support.php" class="">Support</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="Backend/logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div>
</nav>

<main class="main-content">
    <div class="profile-container">
        <i class="bi bi-person-circle profile-icon"></i>
        <h1>My Account</h1>
        <?php if (!empty($error)) : ?>
    <p style="color:red; text-align:center;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)) : ?>
    <p style="color:green; text-align:center;"><?php echo $success; ?></p>
<?php endif; ?>
        <form method="POST" action="account.php" class="profile-form">

            <div class="form-group">
                <label for="Fname">First Name</label>
                <input type="text" name="Fname" id="Fname" value="<?php echo $fname; ?>" required>
            </div>

            <div class="form-group">
                <label for="Lname">Last Name</label>
                <input type="text" name="Lname" id="Lname" value="<?php echo $lname; ?>" required>
            </div>


            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>

            <button type="button" class="btn btn-danger" id="logoutBtn">
                Sign Out
            </button>

        </form>
        <!-- The "Your Streak Badges" section has been removed as requested -->
    </div>
</main>

<script src="../js/account.js"></script>
</body>
</html>