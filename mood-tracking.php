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
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="diary.php">Diary</a></li>
<li><a href="daily-prompt.php">Prompts</a></li>
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


<div class="card" style="margin-bottom:2rem;">

<h2 style="margin-bottom:1rem;">Mood Overview</h2>

<div class="features">

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#22c55e,#4ade80); color:white;">
😊
</div>
<h3>Happy Days</h3>
<p>12 days this month</p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa); color:white;">
😐
</div>
<h3>Neutral Days</h3>
<p>8 days this month</p>
</div>

<div class="feature-card">
<div class="feature-icon" style="background:linear-gradient(135deg,#ef4444,#f87171); color:white;">
😢
</div>
<h3>Low Mood Days</h3>
<p>5 days this month</p>
</div>

</div>

</div>


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

<tr style="border-bottom:1px solid #f1f5f9;">
<td style="padding:0.75rem;">Today</td>
<td style="padding:0.75rem;">😊 Happy</td>
<td style="padding:0.75rem;">Had a productive day</td>
</tr>

<tr style="border-bottom:1px solid #f1f5f9;">
<td style="padding:0.75rem;">Yesterday</td>
<td style="padding:0.75rem;">😐 Neutral</td>
<td style="padding:0.75rem;">Normal day</td>
</tr>

<tr style="border-bottom:1px solid #f1f5f9;">
<td style="padding:0.75rem;">2 Days Ago</td>
<td style="padding:0.75rem;">😢 Sad</td>
<td style="padding:0.75rem;">Felt tired and stressed</td>
</tr>

<tr>
<td style="padding:0.75rem;">3 Days Ago</td>
<td style="padding:0.75rem;">😊 Happy</td>
<td style="padding:0.75rem;">Spent time with friends</td>
</tr>

</tbody>

</table>

</div>


<div class="card">

<h2 style="margin-bottom:1.5rem;">Insights</h2>

<p style="margin-bottom:1rem;">
• Your most common mood this month is <strong>Happy</strong>.
</p>

<p style="margin-bottom:1rem;">
• You tend to feel better after writing in your <strong>Diary</strong>.
</p>

<p>
• Consider joining a <strong>Support Group</strong> when you're feeling low.
</p>

<div style="margin-top:2rem;">

<a href="dashboard.html" class="btn btn-primary btn-large" style="width:100%;">
Log Today's Mood
</a>

</div>

</div>

</div>

</body>
</html>