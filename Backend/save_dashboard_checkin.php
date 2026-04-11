<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

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
$mood = trim($_POST['mood'] ?? '');
$notes = trim($_POST['notes'] ?? '');
$send_message = isset($_POST['send_message']) ? (int) $_POST['send_message'] : 0;

$allowed_moods = ['happy', 'sad', 'anxious', 'angry', 'neutral', 'disappointed'];

if (!in_array($mood, $allowed_moods, true)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid mood selected'
    ]);
    exit;
}

if ($send_message === 1 && $notes === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please write a message before sending it anonymously'
    ]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO moodentries (user_id, mood, notes, mood_date, created_at)
    VALUES (?, ?, ?, CURDATE(), NOW())
");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("iss", $user_id, $mood, $notes);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not save mood entry: ' . $stmt->error
    ]);
    exit;
}

$stmt->close();

$message_delivery_status = 'not_requested';

if ($send_message === 1 && $notes !== '') {
    $receiver_query = $conn->prepare("
        SELECT me.user_id
        FROM moodentries me
        INNER JOIN (
            SELECT user_id, MAX(created_at) AS latest_created_at
            FROM moodentries
            GROUP BY user_id
        ) latest
        ON me.user_id = latest.user_id
        AND me.created_at = latest.latest_created_at
        WHERE me.mood = ?
          AND me.user_id != ?
        ORDER BY RAND()
        LIMIT 1
    ");

    if ($receiver_query) {
        $receiver_query->bind_param("si", $mood, $user_id);
        $receiver_query->execute();
        $receiver_result = $receiver_query->get_result();

        if ($receiver_result && $receiver_result->num_rows > 0) {
            $receiver = $receiver_result->fetch_assoc();
            $receiver_id = (int) $receiver['user_id'];

            $insert_message = $conn->prepare("
                INSERT INTO anonymous_messages (sender_id, receiver_id, mood, message_text, is_read, created_at)
                VALUES (?, ?, ?, ?, 0, NOW())
            ");

            if ($insert_message) {
                $insert_message->bind_param("iiss", $user_id, $receiver_id, $mood, $notes);

                if ($insert_message->execute()) {
                    $message_delivery_status = 'delivered';
                } else {
                    $message_delivery_status = 'failed';
                }

                $insert_message->close();
            } else {
                $message_delivery_status = 'failed';
            }
        } else {
            $message_delivery_status = 'no_match';
        }

        $receiver_query->close();
    } else {
        $message_delivery_status = 'failed';
    }
}

$response_message = 'Mood check-in saved successfully.';

if ($message_delivery_status === 'delivered') {
    $response_message = 'Mood check-in saved and anonymous message delivered.';
} elseif ($message_delivery_status === 'no_match') {
    $response_message = 'Mood check-in saved. No matching user is available right now.';
} elseif ($message_delivery_status === 'failed') {
    $response_message = 'Mood check-in saved, but the anonymous message could not be delivered.';
}

echo json_encode([
    'status' => 'success',
    'message' => $response_message
]);

$conn->close();
?>