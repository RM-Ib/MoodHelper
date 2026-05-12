<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$reply_id = $_POST['reply_id'] ?? null;

if (!$reply_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing reply_id']);
    exit();
}

// Verify ownership
$stmt = $conn->prepare("SELECT user_id, post_id FROM group_replies WHERE reply_id = ?");
$stmt->bind_param("i", $reply_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['user_id'] != $user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit();
}

$post_id = $row['post_id'];

// Delete the reply
$stmt = $conn->prepare("DELETE FROM group_replies WHERE reply_id = ?");
$stmt->bind_param("i", $reply_id);
$stmt->execute();

// Decrement reply count on the post (if you maintain a counter)
$conn->query("UPDATE group_posts SET replies_count = replies_count - 1 WHERE post_id = $post_id");

echo json_encode(['status' => 'success']);