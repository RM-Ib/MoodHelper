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
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$mood = trim($_POST['mood'] ?? '');

$allowed_moods = ['happy', 'sad', 'anxious', 'calm', 'grateful'];

if ($content === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Diary entry content cannot be empty'
    ]);
    exit;
}

if ($mood !== '' && !in_array($mood, $allowed_moods, true)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid mood selected'
    ]);
    exit;
}

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
    INSERT INTO diaryentries (user_id, title, content, mood, entry_date, created_at)
    VALUES (?, ?, ?, ?, CURDATE(), NOW())
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("isss", $user_id, $encryptedTitle, $encryptedContent, $mood);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not save entry: ' . $stmt->error
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

/* Mirror selected mood into moodentries */
if ($mood !== '') {
    $moodNotes = 'Diary entry mood';
    $moodStmt = $conn->prepare("
        INSERT INTO moodentries (user_id, mood, notes, mood_date, created_at)
        VALUES (?, ?, ?, CURDATE(), NOW())
    ");

    if ($moodStmt) {
        $moodStmt->bind_param("iss", $user_id, $mood, $moodNotes);
        $moodStmt->execute();
        $moodStmt->close();
    }
}

echo json_encode([
    'status' => 'success',
    'message' => 'Entry saved successfully'
]);

$conn->close();
?>