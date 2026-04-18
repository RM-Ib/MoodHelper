<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
include 'Backend/db_connect.php';

$user_id = $_SESSION['user_id'];

// Handle new reflection submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reflection'])) {
    $reflection = trim($_POST['reflection']);
    if (!empty($reflection)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, page, content, is_anonymous) VALUES (?, 'reflection-board', ?, 1)");
        $stmt->bind_param("is", $user_id, $reflection);
        $stmt->execute();
        $stmt->close();
        header("Location: reflection-board.php");
        exit();
    }
}

// Fetch posts with heart and reply counts
$posts = [];
$sql = "SELECT p.post_id, p.user_id, p.content, p.created_at,
               (SELECT COUNT(*) FROM post_hearts ph WHERE ph.post_id = p.post_id) AS hearts_count,
               (SELECT COUNT(*) FROM post_replies r WHERE r.post_id = p.post_id) AS replies_count
        FROM posts p
        WHERE page = 'reflection-board'
        ORDER BY created_at DESC";
$result = $conn->query($sql);
$post_ids = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
    $post_ids[] = $row['post_id'];
}

// Fetch all replies for the displayed posts in a single query
$all_replies = [];
if (!empty($post_ids)) {
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $stmt = $conn->prepare("SELECT reply_id, post_id, user_id, content, created_at 
                            FROM post_replies 
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

// For each post, build anonymous numbering map
$anon_maps = []; // post_id => [user_id => number]
foreach ($posts as $post) {
    $post_id = $post['post_id'];
    $map = [];
    $counter = 1;
    
    // Post author
    $map[$post['user_id']] = $counter++;
    
    // Replies in order
    if (isset($all_replies[$post_id])) {
        foreach ($all_replies[$post_id] as $reply) {
            if (!isset($map[$reply['user_id']])) {
                $map[$reply['user_id']] = $counter++;
            }
        }
    }
    $anon_maps[$post_id] = $map;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reflection Board - MoodHelper</title>
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
            <li><a href="reflection-board.php" class="active">Reflection Board</a></li>
            <li><a href="groups.php">Groups</a></li>
            <li><a href="mood-support.php">Support</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>

        <div class="nav-buttons">
            <a href="account.php" class="btn btn-secondary">Account</a>
            <a href="Backend/logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div>
</nav>

<div class="container" style="padding:3rem 2rem; max-width:900px;">

<!-- Header Card -->
<div class="card" style="margin-bottom:2rem; text-align:center; background: linear-gradient(135deg, rgba(236, 72, 153, 0.03), rgba(236, 72, 153, 0.08));">
    <div style="font-size:3rem; margin-bottom:1rem;">📝</div>
    <h1 style="font-size:2rem; margin-bottom:0.5rem;">Reflection Board</h1>
    <p style="color:var(--text-secondary); font-size:1.125rem;">Share your thoughts and reflections. Posts are anonymous.</p>
</div>

<!-- New Reflection Form -->
<div class="card" style="margin-bottom:2rem;">
    <form method="POST" id="reflectionForm">
        <textarea id="reflectionContent" name="reflection" rows="4" maxlength="300" placeholder="Write something..." required style="width:100%; padding:0.75rem; font-size:1rem; border-radius:0.5rem; border:1px solid var(--border-light);"></textarea>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.5rem;">
            <div id="charCount" style="color: var(--text-secondary); font-size:0.875rem;">0 / 300</div>
            <button type="submit" class="btn btn-primary">Post Reflection</button>
        </div>
    </form>
</div>

<h2 style="font-size:1.5rem; margin-bottom:1.5rem;">Community Reflections</h2>
<div id="postsContainer">
<?php if (!empty($posts)): ?>
<?php foreach ($posts as $post): ?>
    <?php 
        $post_id = $post['post_id'];
        $map = $anon_maps[$post_id];
        $author_number = $map[$post['user_id']];
    ?>
<div class="card" data-postid="<?= $post_id ?>" style="margin-bottom:1.5rem; position:relative;">
    <div style="display:flex; gap:1rem;">
        <div style="flex-shrink:0;">
            <div style="width:40px; height:40px; background: linear-gradient(135deg, #ec4899, #be185d); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:600;">
                <?= $author_number ?>
            </div>
        </div>
        <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                <span style="font-weight:500; color:var(--text-primary);">Anonymous #<?= $author_number ?></span>
                <span style="color:var(--text-secondary); font-size:0.875rem;"><?= date("M d, Y H:i", strtotime($post['created_at'])) ?></span>
                <?php if ($post['user_id'] == $user_id): ?>
                <button class="deleteBtn" style="margin-left:auto; background:none;border:none;color:red;cursor:pointer;">Delete</button>
                <?php endif; ?>
            </div>
            <p style="color:var(--text-secondary); line-height:1.7; margin-bottom:0.75rem;"><?= htmlspecialchars($post['content']) ?></p>
            
            <!-- Actions -->
            <div style="display:flex; gap:1.5rem; align-items:center; padding-top:0.75rem; border-top:1px solid var(--border-light);">
                <button class="heartBtn" style="display:flex; align-items:center; gap:0.5rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.5rem 0.75rem; border-radius:0.5rem;">
                    <span>💜</span>
                    <span style="font-weight:500;" class="heartCount"><?= $post['hearts_count'] ?></span>
                </button>
                <button class="replyToggleBtn" style="display:flex; align-items:center; gap:0.5rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.5rem 0.75rem; border-radius:0.5rem;">
                    <span>💬</span>
                    <span style="font-weight:500;">
                        <?php 
                            if($post['replies_count'] == 0) echo "Reply";
                            elseif($post['replies_count'] == 1) echo "1 reply";
                            else echo $post['replies_count'] . " replies";
                        ?>
                    </span>
                </button>
            </div>

            <!-- Replies Container -->
            <div class="repliesContainer" style="display:none; margin-top:1rem;"></div>
            <div class="replyFormContainer" style="display:none; margin-top:0.5rem;">
                <textarea class="replyInput" rows="2" placeholder="Write a reply..." style="width:100%; padding:0.5rem; font-size:0.9rem; border-radius:0.5rem; border:1px solid var(--border-light); margin-bottom:0.5rem;"></textarea>
                <button class="replyBtn btn btn-secondary btn-small">Reply Anonymously</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div style="text-align:center; padding:4rem 2rem; color:var(--text-secondary);">
    <div style="font-size:3rem; margin-bottom:1rem;">💬</div>
    <h3 style="font-size:1.25rem; margin-bottom:0.5rem;">No reflections yet</h3>
    <p>Be the first to share your thoughts.</p>
</div>
<?php endif; ?>
</div>
</div>

<script>
// Store current user ID and anon maps for JS
const currentUserId = <?= json_encode($user_id) ?>;
const anonMaps = <?= json_encode($anon_maps) ?>;

// Character count
const textarea = document.getElementById("reflectionContent");
const charCount = document.getElementById("charCount");
textarea.addEventListener("input", () => {
    charCount.textContent = textarea.value.length + " / 300";
});

// Heart Like/Unlike
document.querySelectorAll(".heartBtn").forEach(btn => {
    btn.addEventListener("click", () => {
        const card = btn.closest(".card");
        const postId = card.dataset.postid;
        fetch("Backend/heart_post.php", {
            method: "POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"},
            body:"post_id=" + postId
        }).then(res=>res.json()).then(data=>{
            if(data.status==="success"){
                btn.querySelector(".heartCount").innerText=data.total_hearts;
                btn.style.color = data.action==="liked"?"red":"var(--text-secondary)";
            }
        });
    });
});

// Delete post
document.querySelectorAll(".deleteBtn").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        const card = btn.closest(".card");
        const postId = card.dataset.postid;
        if(confirm("Delete this post?")){
            fetch("Backend/delete_post.php",{
                method:"POST",
                headers: {"Content-Type":"application/x-www-form-urlencoded"},
                body:"post_id="+postId
            }).then(res=>res.json()).then(data=>{
                if(data.status==="success"){
                    card.remove();
                }
            });
        }
    });
});

// Function to render replies with anonymous numbers
function renderReplies(card, replies){
    const postId = card.dataset.postid;
    const map = anonMaps[postId] || {};
    const repliesContainer = card.querySelector(".repliesContainer");
    const toggleBtn = card.querySelector(".replyToggleBtn");
    repliesContainer.innerHTML = "";

    // Update reply count
    if(replies.length === 0) toggleBtn.querySelector("span:last-child").innerText = "Reply";
    else if(replies.length === 1) toggleBtn.querySelector("span:last-child").innerText = "1 reply";
    else toggleBtn.querySelector("span:last-child").innerText = replies.length + " replies";

    replies.forEach(reply => {
        const replyNumber = map[reply.user_id] || '?';
        const div = document.createElement("div");
        div.style.background = "#f9f9f9";
        div.style.border = "1px solid var(--border-light)";
        div.style.borderRadius = "0.5rem";
        div.style.padding = "0.5rem 0.75rem";
        div.style.marginBottom = "0.5rem";
        div.style.display = "flex";
        div.style.justifyContent = "space-between";
        div.style.alignItems = "center";

        const contentDiv = document.createElement("div");
        contentDiv.innerHTML = `<span style="color:var(--text-primary); font-weight:500;">Anonymous #${replyNumber}</span>: ${reply.content} <br> <span style="color:var(--text-secondary); font-size:0.75rem;">${reply.created_at}</span>`;
        div.appendChild(contentDiv);

        // Delete button if user owns reply
        if(reply.user_id == currentUserId){
            const delBtn = document.createElement("button");
            delBtn.innerText = "Delete";
            delBtn.style.background = "none";
            delBtn.style.border = "none";
            delBtn.style.color = "red";
            delBtn.style.cursor = "pointer";
            delBtn.style.fontSize = "0.75rem";
            delBtn.addEventListener("click", ()=>{
                if(confirm("Delete this reply?")){
                    fetch("Backend/delete_reply.php",{
                        method:"POST",
                        headers: {"Content-Type":"application/x-www-form-urlencoded"},
                        body: "reply_id=" + reply.reply_id
                    }).then(res=>res.json()).then(data=>{
                        if(data.status==="success"){
                            div.remove();
                            const newCount = repliesContainer.querySelectorAll("div").length;
                            if(newCount === 0) toggleBtn.querySelector("span:last-child").innerText = "Reply";
                            else if(newCount === 1) toggleBtn.querySelector("span:last-child").innerText = "1 reply";
                            else toggleBtn.querySelector("span:last-child").innerText = newCount + " replies";
                        }
                    });
                }
            });
            div.appendChild(delBtn);
        }

        repliesContainer.appendChild(div);
    });

    repliesContainer.style.display = "block";
}

// Reply toggle
document.querySelectorAll(".replyToggleBtn").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        const card = btn.closest(".card");
        const form = card.querySelector(".replyFormContainer");
        const repliesContainer = card.querySelector(".repliesContainer");

        const isVisible = repliesContainer.style.display === "block";
        repliesContainer.style.display = isVisible ? "none" : "block";
        form.style.display = isVisible ? "none" : "block";

        if(!isVisible){
            fetch("Backend/get_replies.php", {
                method:"POST",
                headers: {"Content-Type":"application/x-www-form-urlencoded"},
                body:"post_id="+card.dataset.postid
            }).then(res=>res.json()).then(data=>{
                renderReplies(card, data);
            });
        }
    });
});

// Post reply
document.querySelectorAll(".replyBtn").forEach(btn=>{
    btn.addEventListener("click",(e)=>{
        const card = btn.closest(".card");
        const input = card.querySelector(".replyInput");
        const value = input.value.trim();
        if(!value) return;

        fetch("Backend/reply_post.php",{
            method:"POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"},
            body:"post_id="+card.dataset.postid+"&content="+encodeURIComponent(value)
        }).then(res=>res.json()).then(data=>{
            if(data.status==="success"){
                input.value="";
                card.querySelector(".replyFormContainer").style.display="none";

                fetch("Backend/get_replies.php", {
                    method:"POST",
                    headers: {"Content-Type":"application/x-www-form-urlencoded"},
                    body:"post_id="+card.dataset.postid
                }).then(res=>res.json()).then(data=>{
                    // Refresh map from server (in a real app you'd update map, but here we'll refetch)
                    // For simplicity, we can reload the page or fetch updated map; but we'll just re-render.
                    // To keep numbers consistent, we'd need to update anonMaps, but for now reload is safest.
                    // However, to avoid full reload, we can just re-fetch posts? 
                    // Let's keep it simple: reload page after successful reply.
                    location.reload();
                });
            }
        });
    });
});
</script>
</body>
</html>