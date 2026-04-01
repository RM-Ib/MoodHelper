<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error', 'message'=>'Not logged in']);
    exit();
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$reply_id = intval($_POST['reply_id']);
if ($reply_id <= 0) {
    echo json_encode(['status'=>'error', 'message'=>'Invalid reply']);
    exit();
}

// Only allow deletion if the reply belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM post_replies WHERE reply_id = ? AND user_id = ?");
$stmt->bind_param("ii", $reply_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error', 'message'=>'Cannot delete this reply']);
}

$stmt->close();
$conn->close();
?>