<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "moodhelperdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
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

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    if (empty($_POST['confirm_delete'])) {
        $error = "You must confirm the deletion by checking the box.";
    } else {
        $tables = [
            'moodentries' => ['user_id'],
            'diaryentries' => ['user_id'],
            'anonymous_messages' => ['sender_id', 'receiver_id'],
            'chat_messages' => ['user_id'],
            'dailypromptanswers' => ['user_id'],
            'post_replies' => ['user_id'],
            'post_hearts' => ['user_id'],
            'posts' => ['user_id'],
            'group_replies' => ['user_id'],
            'group_post_hearts' => ['user_id'],
            'group_posts' => ['user_id'],
        ];

        foreach ($tables as $table => $columns) {
            foreach ($columns as $col) {
                $sql = "DELETE FROM $table WHERE $col = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        session_destroy();
        header("Location: index.html?deleted=1");
        exit();
    }
}

// Fetch user data for the form
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

    <link rel="stylesheet" href="css/styles.css">
    <style>
        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
        }
        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .danger-zone {
            margin-top: 3rem;
            padding: 2rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 1rem;
        }
        .danger-zone h3 {
            color: #dc2626;
            margin-bottom: 1rem;
        }
    </style>
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
            <li><a href="mood-support.php">Support</a></li>
        </ul>
        <div class="nav-buttons">
            <!-- FIXED: path updated to go up one level to the Backend folder -->
            <a href="../Backend/logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div>
</nav>

<main class="main-content">
    <div class="profile-container" style="max-width: 600px;">
        <i class="bi bi-person-circle profile-icon"></i>
        <h1>My Account</h1>
        <?php if (!empty($error)) : ?>
            <p style="color:red; text-align:center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <p style="color:green; text-align:center;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form method="POST" action="account.php" class="profile-form">
            <input type="hidden" name="update_profile" value="1">
            <div class="form-group">
                <label for="Fname">First Name</label>
                <input type="text" name="Fname" id="Fname" value="<?php echo htmlspecialchars($fname); ?>" required>
            </div>
            <div class="form-group">
                <label for="Lname">Last Name</label>
                <input type="text" name="Lname" id="Lname" value="<?php echo htmlspecialchars($lname); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <!-- Sign Out button – now works via inline click handler -->
        <button type="button" class="btn btn-secondary" id="logoutBtn" style="margin-top:1rem; width:100%;">Sign Out</button>

        <!-- Delete Account Section -->
        <div class="danger-zone">
            <h3>Delete My Account</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                This will permanently delete all your data: mood logs, diary entries, reflections, messages, group activity, and account. This action <strong>cannot</strong> be undone.
            </p>
            <form method="POST" action="account.php" onsubmit="return confirm('Are you absolutely sure? Your data cannot be recovered.');">
                <input type="hidden" name="delete_account" value="1">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <input type="checkbox" name="confirm_delete" id="confirm_delete" value="1" required>
                    <label for="confirm_delete">I understand that my entire account and all associated data will be permanently removed.</label>
                </div>
                <button type="submit" class="btn-danger" style="width:100%;">Delete My Account</button>
            </form>
        </div>
    </div>
</main>

<!-- Load external JS (if account.js exists) -->
<script src="../js/account.js"></script>

<!-- Inline fallback to ensure the Sign Out button always works -->
<script>
    document.getElementById('logoutBtn').addEventListener('click', function() {
        window.location.href = 'Backend/logout.php';
    });
</script>
</body>
</html>