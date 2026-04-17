<?php
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "moodhelperdb");
require_once "ai-crypto.php";

$user_id = 1;

$result = $conn->query("SELECT role, message FROM chat_messages WHERE user_id = $user_id ORDER BY created_at ASC");

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "role" => $row["role"],
        "content" => decryptMessage($row["message"])
    ];
}

echo json_encode($messages);