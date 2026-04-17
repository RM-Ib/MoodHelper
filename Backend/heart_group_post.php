<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id'] ?? 0);

if ($post_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'invalid post']);
    exit;
}

/* check if already liked */
$stmt = $conn->prepare("SELECT heart_id FROM group_post_hearts WHERE post_id=? AND user_id=?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    /* UNLIKE */
    $del = $conn->prepare("DELETE FROM group_post_hearts WHERE post_id=? AND user_id=?");
    $del->bind_param("ii", $post_id, $user_id);
    $del->execute();

    $conn->query("UPDATE group_posts SET hearts_count = hearts_count - 1 WHERE post_id=$post_id");
    $action = "unliked";
} else {
    /* LIKE */
    $ins = $conn->prepare("INSERT INTO group_post_hearts (post_id, user_id) VALUES (?, ?)");
    $ins->bind_param("ii", $post_id, $user_id);
    $ins->execute();

    $conn->query("UPDATE group_posts SET hearts_count = hearts_count + 1 WHERE post_id=$post_id");
    $action = "liked";
}

/* get updated count */
$count = $conn->query("SELECT hearts_count FROM group_posts WHERE post_id=$post_id")
              ->fetch_assoc()['hearts_count'];

echo json_encode([
    "status" => "success",
    "action" => $action,
    "total_hearts" => $count
]);