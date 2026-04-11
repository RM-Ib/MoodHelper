<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$prompt_id = isset($_POST['prompt_id']) ? intval($_POST['prompt_id']) : 0;

if ($prompt_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid prompt ID"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM dailypromptanswers WHERE user_id = ? AND prompt_id = ?");
$stmt->bind_param("ii", $user_id, $prompt_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Today's answer was removed"]);
} else {
    echo json_encode(["status" => "error", "message" => "Could not skip today"]);
}
?>