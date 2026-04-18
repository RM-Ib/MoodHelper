<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';
include 'diary_crypto.php';

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
$entry_id = (int) ($_POST['entry_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($entry_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid entry ID'
    ]);
    exit;
}

if ($content === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Diary entry content cannot be empty'
    ]);
    exit;
}

$checkStmt = $conn->prepare("
    SELECT entry_id
    FROM diaryentries
    WHERE entry_id = ? AND user_id = ?
");

if (!$checkStmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$checkStmt->bind_param("ii", $entry_id, $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $checkStmt->close();
    echo json_encode([
        'status' => 'error',
        'message' => 'Entry not found'
    ]);
    exit;
}

$checkStmt->close();

try {
    $encryptedTitle = encryptDiaryText($title);
    $encryptedContent = encryptDiaryText($content);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Encryption failed'
    ]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE diaryentries
    SET title = ?, content = ?
    WHERE entry_id = ? AND user_id = ?
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ssii", $encryptedTitle, $encryptedContent, $entry_id, $user_id);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not update entry: ' . $stmt->error
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

echo json_encode([
    'status' => 'success',
    'message' => 'Entry updated successfully'
]);

$conn->close();
?>