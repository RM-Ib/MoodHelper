<?php
session_start();
header('Content-Type: application/json');

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$prompt_id = isset($_POST['prompt_id']) ? (int) $_POST['prompt_id'] : 0;
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

if ($prompt_id <= 0 || $answer === '') {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Prompt and answer are required']);
    exit;
}

$stmt = $conn->prepare("SELECT prompt_id, prompt_text FROM dailyprompts WHERE prompt_id = ? LIMIT 1");
$stmt->bind_param('i', $prompt_id);
$stmt->execute();
$promptResult = $stmt->get_result();
if (!$promptResult || $promptResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Prompt not found']);
    exit;
}
$prompt = $promptResult->fetch_assoc();
$stmt->close();

$checkStmt = $conn->prepare(
    "SELECT answer_id
     FROM dailypromptanswers
     WHERE user_id = ? AND prompt_id = ? AND DATE(answered_at) = CURDATE()
     LIMIT 1"
);
$checkStmt->bind_param('ii', $user_id, $prompt_id);
$checkStmt->execute();
$existing = $checkStmt->get_result();

if ($existing && $existing->num_rows > 0) {
    $existingRow = $existing->fetch_assoc();
    $answer_id = (int) $existingRow['answer_id'];
    $checkStmt->close();

    $updateStmt = $conn->prepare("UPDATE dailypromptanswers SET answer = ?, answered_at = NOW() WHERE answer_id = ?");
    $updateStmt->bind_param('si', $answer, $answer_id);
    $ok = $updateStmt->execute();
    $updateStmt->close();

    if (!$ok) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update answer']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Answer updated successfully',
        'action' => 'updated',
        'answer' => [
            'answer_id' => $answer_id,
            'prompt_id' => $prompt_id,
            'prompt_text' => $prompt['prompt_text'],
            'answer' => $answer,
            'answered_at' => date('Y-m-d H:i:s')
        ]
    ]);
    exit;
}
$checkStmt->close();

$insertStmt = $conn->prepare("INSERT INTO dailypromptanswers (user_id, prompt_id, answer, answered_at) VALUES (?, ?, ?, NOW())");
$insertStmt->bind_param('iis', $user_id, $prompt_id, $answer);
$ok = $insertStmt->execute();
$new_answer_id = $insertStmt->insert_id;
$insertStmt->close();

if (!$ok) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save answer']);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Answer saved successfully',
    'action' => 'created',
    'answer' => [
        'answer_id' => $new_answer_id,
        'prompt_id' => $prompt_id,
        'prompt_text' => $prompt['prompt_text'],
        'answer' => $answer,
        'answered_at' => date('Y-m-d H:i:s')
    ]
]);
