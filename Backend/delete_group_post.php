<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

include 'db_connect.php'; // uses $conn (or adjust to your own connection)

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'] ?? null;

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing post_id']);
    exit();
}

// Verify ownership
$stmt = $conn->prepare("SELECT user_id FROM group_posts WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['user_id'] != $user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit();
}

// Delete replies first (optional cascade, depending on DB setup)
$conn->query("DELETE FROM group_replies WHERE post_id = $post_id");

// Delete hearts
$conn->query("DELETE FROM group_post_hearts WHERE post_id = $post_id");

// Delete the post
$stmt = $conn->prepare("DELETE FROM group_posts WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();

echo json_encode(['status' => 'success']);