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
$entry_id = (int)($_POST['entry_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$mood = trim($_POST['mood'] ?? '');

$allowed_moods = ['happy', 'sad', 'anxious', 'calm', 'grateful', 'angry', 'neutral', 'disappointed'];
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

// if ($mood !== '' && !in_array($mood, $allowed_moods, true)) {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Invalid mood selected'
//     ]);
//     exit;
// }

if ($mood === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mood is required'
    ]);
    exit;
}

if (!in_array($mood, $allowed_moods, true)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid mood selected'
    ]);
    exit;
}

/* Check ownership and old mood */
$checkStmt = $conn->prepare("
    SELECT mood
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

$oldRow = $checkResult->fetch_assoc();
$oldMood = $oldRow['mood'] ?? '';
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
    SET title = ?, content = ?, mood = ?
    WHERE entry_id = ? AND user_id = ?
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("sssii", $encryptedTitle, $encryptedContent, $mood, $entry_id, $user_id);

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

/* Mirror changed mood into moodentries */
// if ($mood !== '' && $mood !== $oldMood) {
//     $moodNotes = 'Diary entry mood update';
//     $moodStmt = $conn->prepare("
//         INSERT INTO moodentries (user_id, mood, notes, mood_date, created_at)
//         VALUES (?, ?, ?, CURDATE(), NOW())
//     ");

//     if ($moodStmt) {
//         $moodStmt->bind_param("iss", $user_id, $mood, $moodNotes);
//         $moodStmt->execute();
//         $moodStmt->close();
//     }
// }

echo json_encode([
    'status' => 'success',
    'message' => 'Entry updated successfully'
]);

$conn->close();
?>