<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Not logged in']));
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$content = trim($_POST['content']);

// Validate input
if ($post_id <= 0 || !$content) {
    exit(json_encode(['status' => 'error', 'message' => 'Invalid input']));
}

// Insert reply into reflection post replies table
$stmt = $conn->prepare("INSERT INTO post_replies (post_id, user_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $user_id, $content);
$stmt->execute();
$stmt->close();

// Return success
echo json_encode(['status' => 'success']);
?>