<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

header("Content-Type: application/json");

// 🔐 OPENROUTER KEY
$apiKey = "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";

// 🗄️ DB CONNECTION
$conn = new mysqli("localhost", "root", "", "moodhelperdb");
if ($conn->connect_error) {
    echo json_encode(["reply" => "Database connection failed"]);
    exit;
}

// 🔐 INCLUDE FILES
require_once "ai-crypto.php";
require_once "chatbot_safety.php";

// 📥 INPUT
$data = json_decode(file_get_contents("php://input"), true);
$history = $data["history"] ?? [];
$mood = $data["mood"] ?? "normal";

// 🧠 LAST USER MESSAGE
$lastUserMessage = "";
if (!empty($history)) {
    $lastMessage = end($history);
    $lastUserMessage = $lastMessage["content"] ?? "";
}

// 🚨 SAFETY CHECK
$risk = detectRiskLevel($lastUserMessage);

if ($risk === "high") {
    echo json_encode(["reply" => getHighRiskResponse()]);
    exit;
}

// 🧠 SYSTEM PROMPT
$systemPrompt = "
You are MoodHelper AI.

Personality:
- Emotionally intelligent
- Balanced between friendly and professional
- Natural, human tone
- Not a therapist

STRICT RULES:
- NEVER repeat the user's words
- NO phrases like 'I understand' or 'I hear you'
- NO generic advice repetition
- NO robotic tone

STYLE:
- Short (2–4 sentences)
- Natural, conversational
- Slightly casual but respectful

BEHAVIOR:
- If stressed → give simple, practical help
- If sad → acknowledge naturally + ask 1 thoughtful question
- If overwhelmed → ground + simplify
- If user rejects advice → change approach

User mood: $mood
";

// 🧠 BUILD MESSAGES
$messages = [
    ["role" => "system", "content" => $systemPrompt]
];

foreach ($history as $msg) {
    $messages[] = [
        "role" => $msg["role"],
        "content" => $msg["content"]
    ];
}

// 📡 API CALL
$url = "https://openrouter.ai/api/v1/chat/completions";

$requestData = [
    "model" => "meta-llama/llama-3-8b-instruct",
    "messages" => $messages,
    "temperature" => 0.85,
    "max_tokens" => 150
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer $apiKey\r\n",
        "method"  => "POST",
        "content" => json_encode($requestData)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo json_encode(["reply" => "AI connection failed"]);
    exit;
}

$result = json_decode($response, true);
$reply = $result["choices"][0]["message"]["content"] ?? "Error getting response.";

// 🔐 ENCRYPT
$encUser = encryptMessage($lastUserMessage);
$encAI = encryptMessage($reply);

// 💾 SAVE USER MESSAGE
$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, role, message) VALUES (?, 'user', ?)");
$stmt->bind_param("is", $user_id, $encUser);
$stmt->execute();
$stmt->close();

// 💾 SAVE AI MESSAGE
$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, role, message) VALUES (?, 'assistant', ?)");
$stmt->bind_param("is", $user_id, $encAI);
$stmt->execute();
$stmt->close();

$conn->close();

// 📤 OUTPUT
echo json_encode(["reply" => trim($reply)]);