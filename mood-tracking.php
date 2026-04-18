
<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "moodhelperdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch mood entries (latest first, limit 10)
$sql = "SELECT * FROM moodentries WHERE user_id = ? ORDER BY mood_date DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Counters
$happy = 0;
$neutral = 0;
$sad = 0;

$moods = [];

while ($row = $result->fetch_assoc()) {
    $moods[] = $row;

    if ($row['mood'] == 'happy') $happy++;
    elseif ($row['mood'] == 'neutral') $neutral++;
    else $sad++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mood Tracking - MoodHelper</title>

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
<a href="index.html" class="btn btn-primary">Logout</a>
</div>

</div>
</nav>

<div class="container" style="padding:3rem 2rem;">

<div style="margin-bottom:3rem;">
<h1 style="font-size:2.5rem; margin-bottom:0.5rem;">
Your Mood Journey
</h1>

<p style="font-size:1.125rem; color:var(--text-secondary);">
Track your emotions over time and understand your patterns
</p>
</div>


<!-- Mood Overview -->
<div class="card" style="margin-bottom:2rem;">

<h2 style="margin-bottom:1rem;">Mood Overview</h2>

<div class="features">

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#22c55e,#4ade80); color:white;">
😊
</div>
<h3>Happy Days</h3>
<p><?php echo $happy; ?> days</p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa); color:white;">
😐
</div>
<h3>Neutral Days</h3>
<p><?php echo $neutral; ?> days</p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#ef4444,#f87171); color:white;">
😢
</div>
<h3>Low Mood Days</h3>
<p><?php echo $sad; ?> days</p>
</div>

</div>
</div>


<!-- Mood History -->
<div class="card" style="margin-bottom:2rem;">

<h2 style="margin-bottom:1.5rem;">Mood History</h2>

<table style="width:100%; border-collapse:collapse;">

<thead>
<tr style="border-bottom:1px solid #e5e7eb; text-align:left;">
<th style="padding:0.75rem;">Date</th>
<th style="padding:0.75rem;">Mood</th>
<th style="padding:0.75rem;">Notes</th>
</tr>
</thead>

<tbody>

<?php if (count($moods) > 0): ?>
    <?php foreach ($moods as $row): ?>
    <tr style="border-bottom:1px solid #f1f5f9;">
        <td style="padding:0.75rem;">
            <?php echo date("M d, Y", strtotime($row['mood_date'])); ?>
        </td>

        <td style="padding:0.75rem;">
            <?php
            $emoji = "😐";
            if ($row['mood'] == 'happy') $emoji = "😊";
            elseif ($row['mood'] == 'sad') $emoji = "😢";
            elseif ($row['mood'] == 'anxious') $emoji = "😰";
            ?>
            <?php echo $emoji . " " . ucfirst($row['mood']); ?>
        </td>

        <td style="padding:0.75rem;">
            <?php echo !empty($row['notes']) ? $row['notes'] : '-'; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="3" style="padding:1rem; text-align:center; color:gray;">
            No mood entries yet.
        </td>
    </tr>
<?php endif; ?>

</tbody>

</table>

</div>


<!-- Insights -->
<div class="card">

<h2 style="margin-bottom:1.5rem;">Insights</h2>

<p style="margin-bottom:1rem;">
• Your most common mood is <strong>
<?php
if ($happy >= $neutral && $happy >= $sad) echo "Happy";
elseif ($neutral >= $happy && $neutral >= $sad) echo "Neutral";
else echo "Low Mood";
?>
</strong>.
</p>

<p style="margin-bottom:1rem;">
• Keep tracking daily to understand your emotional patterns.
</p>

<p>
• Writing notes helps identify triggers and improvements.
</p>

<div style="margin-top:2rem;">
<a href="dashboard.php" class="btn btn-primary btn-large" style="width:100%;">
Log Today's Mood
</a>
</div>

</div>

</div>

</body>
</html>
