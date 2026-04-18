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

$allowed_moods = ['happy', 'sad', 'anxious', 'calm', 'grateful', 'angry', 'neutral', 'disappointed'];
if ($content === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Diary entry content cannot be empty'
    ]);
    exit;
}

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

/*
 |------------------------------------------------------------
 | Check if user already has a diary entry for today
 |------------------------------------------------------------
*/
$checkStmt = $conn->prepare("
    SELECT entry_id
    FROM diaryentries
    WHERE user_id = ? AND entry_date = CURDATE()
    LIMIT 1
");

if (!$checkStmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$checkStmt->bind_param("i", $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult && $checkResult->num_rows > 0) {
    /*
     |------------------------------------------------------------
     | Entry exists today -> update it
     |------------------------------------------------------------
    */
    $existingEntry = $checkResult->fetch_assoc();
    $entry_id = (int) $existingEntry['entry_id'];
    $checkStmt->close();

    $updateStmt = $conn->prepare("
        UPDATE diaryentries
        SET title = ?, content = ?, mood = ?, created_at = NOW()
        WHERE entry_id = ? AND user_id = ?
    ");

    if (!$updateStmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Prepare failed: ' . $conn->error
        ]);
        exit;
    }

    $updateStmt->bind_param("sssii", $encryptedTitle, $encryptedContent, $mood, $entry_id, $user_id);

    if (!$updateStmt->execute()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Could not update entry: ' . $updateStmt->error
        ]);
        $updateStmt->close();
        $conn->close();
        exit;
    }

    $updateStmt->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Today\'s diary entry updated successfully',
        'action' => 'updated',
        'entry_id' => $entry_id
    ]);

    $conn->close();
    exit;
}

$checkStmt->close();

/*
 |------------------------------------------------------------
 | No entry today -> insert new one
 |------------------------------------------------------------
*/
$insertStmt = $conn->prepare("
    INSERT INTO diaryentries (user_id, title, content, mood, entry_date, created_at)
    VALUES (?, ?, ?, ?, CURDATE(), NOW())
");

if (!$insertStmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$insertStmt->bind_param("isss", $user_id, $encryptedTitle, $encryptedContent, $mood);

if (!$insertStmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not save entry: ' . $insertStmt->error
    ]);
    $insertStmt->close();
    $conn->close();
    exit;
}

$newEntryId = $insertStmt->insert_id;
$insertStmt->close();

echo json_encode([
    'status' => 'success',
    'message' => 'Entry saved successfully',
    'action' => 'created',
    'entry_id' => $newEntryId
]);

$conn->close();
?>