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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$entry_id = (int)($_POST['entry_id'] ?? 0);

if ($entry_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid entry ID'
    ]);
    exit;
}

$stmt = $conn->prepare("
    DELETE FROM diaryentries
    WHERE entry_id = ? AND user_id = ?
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ii", $entry_id, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Entry deleted successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not delete entry: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>