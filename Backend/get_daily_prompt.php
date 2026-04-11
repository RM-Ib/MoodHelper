<?php
session_start();
header('Content-Type: application/json');

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$result = $conn->query("SELECT prompt_id, prompt_text, created_at FROM dailyprompts ORDER BY prompt_id ASC");

if (!$result || $result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'No daily prompts found']);
    exit;
}

$prompts = [];
while ($row = $result->fetch_assoc()) {
    $prompts[] = $row;
}

$dayOfYear = (int) date('z'); // 0-365
$index = $dayOfYear % count($prompts);
$todayPrompt = $prompts[$index];

$user_id = (int) $_SESSION['user_id'];
$todayAnswer = null;

$stmt = $conn->prepare(
    "SELECT answer_id, answer, answered_at
     FROM dailypromptanswers
     WHERE user_id = ? AND prompt_id = ? AND DATE(answered_at) = CURDATE()
     ORDER BY answered_at DESC
     LIMIT 1"
);
$stmt->bind_param('ii', $user_id, $todayPrompt['prompt_id']);
$stmt->execute();
$answerResult = $stmt->get_result();
if ($answerResult && $answerResult->num_rows > 0) {
    $todayAnswer = $answerResult->fetch_assoc();
}
$stmt->close();

echo json_encode([
    'status' => 'success',
    'prompt' => [
        'prompt_id' => (int) $todayPrompt['prompt_id'],
        'prompt_text' => $todayPrompt['prompt_text'],
        'created_at' => $todayPrompt['created_at']
    ],
    'already_answered_today' => $todayAnswer !== null,
    'today_answer' => $todayAnswer
]);
