<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Not logged in'
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* Count unread messages first */
$count_stmt = $conn->prepare("
    SELECT COUNT(*) AS unread_count
    FROM anonymous_messages
    WHERE receiver_id = ? AND is_read = 0
");

if (!$count_stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();
$unread_count = (int) ($count_row['unread_count'] ?? 0);
$count_stmt->close();

/* Load all messages */
$stmt = $conn->prepare("
    SELECT message_id, mood, message_text, created_at
    FROM anonymous_messages
    WHERE receiver_id = ?
    ORDER BY created_at DESC
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'message_id' => $row['message_id'],
        'mood' => $row['mood'],
        'message_text' => $row['message_text'],
        'created_at' => date('F j, Y g:i A', strtotime($row['created_at']))
    ];
}

$stmt->close();

/* Mark unread messages as read after loading */
$mark_read = $conn->prepare("
    UPDATE anonymous_messages
    SET is_read = 1
    WHERE receiver_id = ? AND is_read = 0
");

if ($mark_read) {
    $mark_read->bind_param("i", $user_id);
    $mark_read->execute();
    $mark_read->close();
}

echo json_encode([
    'status' => 'success',
    'unread_count' => $unread_count,
    'messages' => $messages
]);

$conn->close();
?>