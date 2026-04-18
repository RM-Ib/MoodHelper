<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

require_once "ai-crypto.php";

$stmt = $conn->prepare("SELECT role, message FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "role" => $row["role"],
        "content" => decryptMessage($row["message"])
    ];
}

$stmt->close();
$conn->close();

echo json_encode($messages);