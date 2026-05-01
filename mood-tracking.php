<?php
session_start();

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$sqlAll = "SELECT mood, notes, mood_date, created_at FROM moodentries WHERE user_id = ? ORDER BY created_at DESC";
$stmtAll = $conn->prepare($sqlAll);
$stmtAll->bind_param("i", $user_id);
$stmtAll->execute();
$resultAll = $stmtAll->get_result();

$allTimeCounts = [
    'happy' => 0,
    'neutral' => 0,
    'sad' => 0,
    'anxious' => 0,
    'angry' => 0,
    'disappointed' => 0,
    'calm' => 0,
    'grateful' => 0
];

$allMoods = [];
$totalEntriesAll = 0;

while ($row = $resultAll->fetch_assoc()) {
    $mood = strtolower($row['mood']);
    if (isset($allTimeCounts[$mood])) {
        $allTimeCounts[$mood]++;
    } else {
        $allTimeCounts['neutral']++;
    }
    $allMoods[] = $row;
    $totalEntriesAll++;
}
$stmtAll->close();

$recentMoods = array_slice($allMoods, 0, 10);

$sqlChart = "SELECT mood FROM moodentries 
             WHERE user_id = ? 
             AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             ORDER BY created_at DESC";
$stmtChart = $conn->prepare($sqlChart);
$stmtChart->bind_param("i", $user_id);
$stmtChart->execute();
$resultChart = $stmtChart->get_result();

$chartCounts = [
    'happy' => 0,
    'neutral' => 0,
    'sad' => 0,
    'anxious' => 0,
    'angry' => 0,
    'disappointed' => 0,
    'calm' => 0,
    'grateful' => 0
];
$totalEntriesChart = 0;

while ($row = $resultChart->fetch_assoc()) {
    $mood = strtolower($row['mood']);
    if (isset($chartCounts[$mood])) {
        $chartCounts[$mood]++;
    } else {
        $chartCounts['neutral']++;
    }
    $totalEntriesChart++;
}
$stmtChart->close();

$chartLabels = [];
$chartData = [];
foreach ($chartCounts as $mood => $count) {
    $chartLabels[] = ucfirst($mood);
    $chartData[] = $count;
}

$emojiMap = [
    'happy' => '😊',
    'neutral' => '😐',
    'sad' => '😢',
    'anxious' => '😰',
    'angry' => '😠',
    'disappointed' => '😞',
    'calm' => '😌',
    'grateful' => '🙏'
];

$positiveCount = $allTimeCounts['happy'] + $allTimeCounts['calm'] + $allTimeCounts['grateful'];
$neutralCount = $allTimeCounts['neutral'];
$lowCount = $allTimeCounts['sad'] + $allTimeCounts['anxious'] + $allTimeCounts['angry'] + $allTimeCounts['disappointed'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mood Tracking - MoodHelper</title>
<link rel="stylesheet" href="css/styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

<div class="card" style="margin-bottom:2rem;">
<h2 style="margin-bottom:0.5rem;">Mood Distribution</h2>
<p style="color:var(--text-secondary); margin-bottom:1.5rem; font-size:0.95rem;">
📊 Showing entries from the last 30 days (<?php echo $totalEntriesChart; ?> total log<?php echo $totalEntriesChart != 1 ? 's' : ''; ?>)
</p>
<div style="position: relative; height: 300px; width: 100%;">
<canvas id="moodChart"></canvas>
</div>
<?php if ($totalEntriesChart == 0): ?>
<p style="text-align:center; color:var(--text-secondary); margin-top:1rem;">No mood entries in the last 30 days.</p>
<?php endif; ?>
</div>

<div class="card" style="margin-bottom:2rem;">
<h2 style="margin-bottom:1rem;">Quick Summary <span style="font-size:0.9rem; font-weight:normal; color:var(--text-secondary); margin-left:0.5rem;">(all time)</span></h2>
<div class="features">

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#10b981,#34d399); color:white;">🌟</div>
<h3>Positive Moods</h3>
<p><?php echo $positiveCount; ?> time<?php echo $positiveCount != 1 ? 's' : ''; ?></p>
<p style="font-size:0.8rem; color:var(--text-secondary); margin-top:0.25rem;">😊 😌 🙏</p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa); color:white;">😐</div>
<h3>Neutral</h3>
<p><?php echo $neutralCount; ?> time<?php echo $neutralCount != 1 ? 's' : ''; ?></p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#ef4444,#f87171); color:white;">😢</div>
<h3>Low Mood</h3>
<p><?php echo $lowCount; ?> time<?php echo $lowCount != 1 ? 's' : ''; ?></p>
<p style="font-size:0.8rem; color:var(--text-secondary); margin-top:0.25rem;">😢 😰 😠 😞</p>
</div>

</div>
</div>

<div class="card" style="margin-bottom:2rem;">
<h2 style="margin-bottom:1.5rem;">Recent Mood History</h2>
<table style="width:100%; border-collapse:collapse;">
<thead>
<tr style="border-bottom:1px solid #e5e7eb; text-align:left;">
<th style="padding:0.75rem;">Date</th>
<th style="padding:0.75rem;">Mood</th>
<th style="padding:0.75rem;">Notes</th>
</tr>
</thead>
<tbody>
<?php if (count($recentMoods) > 0): ?>
    <?php foreach ($recentMoods as $row): ?>
    <?php
        $mood = strtolower($row['mood']);
        $emoji = $emojiMap[$mood] ?? '😐';
        $displayDate = !empty($row['created_at']) ? $row['created_at'] : $row['mood_date'];
        $formattedDate = date("M d, Y H:i", strtotime($displayDate));
        $note = !empty($row['notes']) ? htmlspecialchars($row['notes']) : '-';
    ?>
    <tr style="border-bottom:1px solid #f1f5f9;">
        <td style="padding:0.75rem;"><?php echo $formattedDate; ?></td>
        <td style="padding:0.75rem;"><?php echo $emoji . ' ' . ucfirst($mood); ?></td>
        <td style="padding:0.75rem;"><?php echo $note; ?></td>
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

<div class="card">
<h2 style="margin-bottom:1.5rem;">Insights</h2>
<?php
$mostFrequent = array_search(max($allTimeCounts), $allTimeCounts);
$mostFreqCount = $allTimeCounts[$mostFrequent];
$mostFreqEmoji = $emojiMap[$mostFrequent] ?? '😐';
?>
<p style="margin-bottom:1rem;">
• Your most common mood overall is <strong><?php echo ucfirst($mostFrequent) . ' ' . $mostFreqEmoji; ?></strong> 
(<?php echo $mostFreqCount; ?> time<?php echo $mostFreqCount != 1 ? 's' : ''; ?>).
</p>
<p style="margin-bottom:1rem;">
• You've logged your mood <strong><?php echo $totalEntriesAll; ?></strong> time<?php echo $totalEntriesAll != 1 ? 's' : ''; ?> in total.
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('moodChart').getContext('2d');
    
    const labels = <?php echo json_encode($chartLabels); ?>;
    const data = <?php echo json_encode($chartData); ?>;
    const backgroundColors = [
        '#22c55e', '#3b82f6', '#ef4444', '#f97316',
        '#dc2626', '#94a3b8', '#06b6d4', '#8b5cf6'
    ];

    if (data.every(count => count === 0)) {
        document.querySelector('#moodChart').style.display = 'none';
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Entries',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors,
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.raw} time${context.raw !== 1 ? 's' : ''}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 },
                    grid: { color: '#e5e7eb' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
</body>
</html>