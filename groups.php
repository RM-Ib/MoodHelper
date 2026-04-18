
<?php
session_start();

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id'])) {
    die("Please log in first.");
}

$result = $conn->query("SELECT * FROM user_groups");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support Groups - MoodHelper</title>
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
<li><a href="diary.php">Diary</a></li>
<li><a href="reflection-board.php">Reflection Board</a></li>
<li><a href="groups.php" class="active">Groups</a></li>
<li><a href="mood-support.php">Support</a></li>
<li><a href="settings.php">Settings</a></li>
</ul>

<div class="nav-buttons">
<a href="account.php" class="btn btn-secondary">Account</a>
<a href="index.html" class="btn btn-primary">Logout</a>
</div>
</div>
</nav>

<div class="container" style="padding: 3rem 2rem;">

<div style="margin-bottom: 3rem;">
<h1 style="font-size: 2.5rem;">Support Groups</h1>
</div>

<div class="features">

<?php while ($group = $result->fetch_assoc()): ?>
<a href="group.php?id=<?php echo $group['group_id']; ?>" 
class="feature-card" style="text-decoration: none; color: inherit; cursor: pointer;">

<div class="feature-icon">
<?php echo $group['icon']; ?>
</div>

<h3><?php echo $group['name']; ?></h3>

<p style="margin-bottom: 1rem;">
<?php echo $group['description']; ?>
</p>

<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
<span>Anonymous • Moderated</span>
<span style="color: var(--primary-purple);">Join →</span>
</div>

</a>
<?php endwhile; ?>

</div>
</div>

</body>
</html>

