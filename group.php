<?php
session_start();

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) die("Connection failed");

if (!isset($_SESSION['user_id'])) die("Login first");

$user_id = $_SESSION['user_id'];
$group_id = $_GET['id'] ?? 0;

/* GROUP */
$stmt = $conn->prepare("SELECT * FROM user_groups WHERE group_id=?");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$group = $stmt->get_result()->fetch_assoc();

if (!$group) die("Group not found");

/* CREATE POST */
if (isset($_POST['new_post'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'];

    $stmt = $conn->prepare("
        INSERT INTO group_posts (group_id, user_id, title, content)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiss", $group_id, $user_id, $title, $content);
    $stmt->execute();

    header("Location: group.php?id=" . $group_id);
    exit();
}

/* REPLY */
if (isset($_POST['reply_post'])) {
    $post_id = $_POST['post_id'];
    $reply = $_POST['reply'];

    $stmt = $conn->prepare("
        INSERT INTO group_replies (post_id, user_id, content)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $post_id, $user_id, $reply);
    $stmt->execute();

    $conn->query("UPDATE group_posts SET replies_count = replies_count + 1 WHERE post_id=$post_id");

    header("Location: group.php?id=" . $group_id);
    exit();
}

/* POSTS */
$posts = $conn->prepare("
    SELECT * FROM group_posts
    WHERE group_id=?
    ORDER BY created_at DESC
");
$posts->bind_param("i", $group_id);
$posts->execute();
$posts = $posts->get_result();

$posts_data = [];
$post_ids = [];
while ($post = $posts->fetch_assoc()) {
    $posts_data[] = $post;
    $post_ids[] = $post['post_id'];
}
$posts = $posts_data;

// Get user's liked post IDs
$liked_posts = [];
if (!empty($post_ids)) {
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $stmt = $conn->prepare("SELECT post_id FROM group_post_hearts WHERE user_id = ? AND post_id IN ($placeholders)");
    $types = 'i' . str_repeat('i', count($post_ids));
    $params = array_merge([$user_id], $post_ids);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $liked_posts[$row['post_id']] = true;
    }
}

// Fetch all replies for displayed posts
$all_replies = [];
if (!empty($post_ids)) {
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $stmt = $conn->prepare("SELECT reply_id, post_id, user_id, content, created_at 
                            FROM group_replies 
                            WHERE post_id IN ($placeholders) 
                            ORDER BY post_id, created_at ASC");
    $types = str_repeat('i', count($post_ids));
    $stmt->bind_param($types, ...$post_ids);
    $stmt->execute();
    $replies_result = $stmt->get_result();
    while ($reply = $replies_result->fetch_assoc()) {
        $all_replies[$reply['post_id']][] = $reply;
    }
}

// Build anonymous numbering per post
$anon_maps = [];
foreach ($posts as $post) {
    $post_id = $post['post_id'];
    $map = [];
    $counter = 1;
    
    // Post author
    $map[$post['user_id']] = $counter++;
    
    // Replies
    if (isset($all_replies[$post_id])) {
        foreach ($all_replies[$post_id] as $reply) {
            if (!isset($map[$reply['user_id']])) {
                $map[$reply['user_id']] = $counter++;
            }
        }
    }
    $anon_maps[$post_id] = $map;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($group['name']); ?> - MoodHelper</title>
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
<a href="Backend/logout.php" class="btn btn-primary">Logout</a>
</div>
</div>
</nav>

<div class="container" style="padding: 3rem 2rem; max-width: 900px;">

<!-- GROUP HEADER -->
<div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(236,72,153,0.03), rgba(236,72,153,0.08));">
<div style="text-align:center;">
    <div style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(236,72,153,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;">
        <?php echo htmlspecialchars($group['icon']); ?>
    </div>
    <h1 style="font-size:2rem;margin-bottom:0.75rem;">
        <?php echo htmlspecialchars($group['name']); ?>
    </h1>
    <p style="color:var(--text-secondary);font-size:1.125rem;max-width:600px;margin:0 auto;">
        <?php echo htmlspecialchars($group['description']); ?>
    </p>
</div>
</div>

<!-- POST FORM -->
<div class="card" style="margin-bottom: 2rem;">
<h3 style="font-size:1.25rem;margin-bottom:1.5rem;">Share Your Thoughts</h3>
<form method="POST">
<input type="hidden" name="new_post" value="1">
<div class="form-group">
<label>Post Title (Optional)</label>
<input type="text" name="title" class="form-control">
</div>
<div class="form-group">
<label>Your Message</label>
<textarea name="content" class="form-control" rows="5" required></textarea>
</div>
<button class="btn btn-primary">Post Anonymously</button>
</form>
</div>

<!-- POSTS -->
<div style="margin-bottom:2rem;">
<h2>Recent Posts</h2>

<?php foreach ($posts as $post): ?>
    <?php 
        $post_id = $post['post_id'];
        $is_liked = isset($liked_posts[$post_id]);
        $map = $anon_maps[$post_id];
        $author_number = $map[$post['user_id']];
    ?>
    <div class="card" style="margin-bottom:1.5rem;">
        <div style="display:flex;gap:1rem;">
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#ec4899,#be185d);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;">
                <?= $author_number ?>
            </div>
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                    <span>Anonymous #<?= $author_number ?></span>
                    <span style="color:var(--text-secondary);font-size:0.875rem;">
                        <?php echo htmlspecialchars($post['created_at']); ?>
                    </span>
                </div>

                <?php if (!empty($post['title'])): ?>
                    <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                <?php endif; ?>

                <p style="color:var(--text-secondary);">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </p>

                <!-- ACTIONS -->
                <div style="display:flex;gap:1.5rem;align-items:center;padding-top:0.75rem;border-top:1px solid var(--border-light);">
                    <button
                        type="button"
                        class="heart-btn"
                        data-post-id="<?php echo $post_id; ?>"
                        style="background:none;border:none;color:var(--text-secondary);cursor:pointer;"
                    >
                        <span id="heart-icon-<?php echo $post_id; ?>">
                            <?php echo $is_liked ? '❤️' : '🤍'; ?>
                        </span>
                        <span id="heart-count-<?php echo $post_id; ?>">
                            <?php echo $post['hearts_count']; ?>
                        </span> Hearts
                    </button>

                    <button type="button"
                        onclick="toggleReplies(<?php echo $post_id; ?>)"
                        style="background:none;border:none;color:var(--text-secondary);cursor:pointer;">
                        💬 <?php echo $post['replies_count']; ?> Replies
                    </button>
                </div>

                <!-- REPLIES SECTION -->
                <div id="replies-<?php echo $post_id; ?>" style="display:none;margin-top:1rem;">
                    <?php
                    if (isset($all_replies[$post_id])) {
                        foreach ($all_replies[$post_id] as $r):
                            $reply_number = $map[$r['user_id']];
                    ?>
                        <div style="margin-bottom:1rem; background:#f9f9f9; padding:0.5rem; border-radius:0.5rem;">
                            <strong>Anonymous #<?= $reply_number ?></strong>
                            <p style="color:var(--text-secondary); margin-top:0.25rem;">
                                <?php echo htmlspecialchars($r['content']); ?>
                            </p>
                            <small style="color:var(--text-secondary);"><?= $r['created_at'] ?></small>
                        </div>
                    <?php 
                        endforeach;
                    }
                    ?>

                    <form method="POST">
                        <input type="hidden" name="reply_post" value="1">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <textarea name="reply" class="form-control" rows="2" required></textarea>
                        <button class="btn btn-secondary" style="margin-top:0.5rem;">Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

</div>
</div>

<script>
function toggleReplies(id) {
    const el = document.getElementById("replies-" + id);
    if (el) {
        el.style.display = (el.style.display === "none") ? "block" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.heart-btn').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            
            const postId = this.dataset.postId;
            if (!postId) return;
            
            const formData = new FormData();
            formData.append("post_id", postId);

            try {
                const response = await fetch("Backend/heart_group_post.php", {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Heart response:', data);

                if (data.status === "success") {
                    const countSpan = document.getElementById("heart-count-" + postId);
                    if (countSpan) {
                        countSpan.innerText = data.total_hearts;
                    }

                    const iconSpan = document.getElementById("heart-icon-" + postId);
                    if (iconSpan) {
                        iconSpan.innerHTML = (data.action === "liked") ? "❤️" : "🤍";
                    }
                } else {
                    console.error('Heart action failed:', data.message);
                    alert('Could not update like. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Check console for details.');
            }
        });
    });
});
</script>

</body>
</html>