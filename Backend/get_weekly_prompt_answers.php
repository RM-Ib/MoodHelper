<?php
session_start();
header('Content-Type: application/json');

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT a.answer_id, a.prompt_id, a.answer, a.answered_at, p.prompt_text
     FROM dailypromptanswers a
     INNER JOIN dailyprompts p ON a.prompt_id = p.prompt_id
     WHERE a.user_id = ? AND a.answered_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     ORDER BY a.answered_at DESC"
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$answers = [];
while ($row = $result->fetch_assoc()) {
    $answers[] = [
        'answer_id' => (int) $row['answer_id'],
        'prompt_id' => (int) $row['prompt_id'],
        'prompt_text' => $row['prompt_text'],
        'answer' => $row['answer'],
        'answered_at' => $row['answered_at'],
        'date_string' => date('M j, Y', strtotime($row['answered_at']))
    ];
}
$stmt->close();

echo json_encode([
    'status' => 'success',
    'count' => count($answers),
    'answers' => $answers
]);
