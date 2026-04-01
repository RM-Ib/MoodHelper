<?php

include 'db_connect.php';

$post_id = intval($_POST['post_id']);
if ($post_id <= 0) exit(json_encode([])); // basic validation

$stmt = $conn->prepare("
    SELECT r.reply_id, r.user_id, r.content, r.created_at, u.first_name, u.last_name
    FROM post_replies r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.post_id = ?
    ORDER BY r.created_at ASC
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$res = $stmt->get_result();

$replies = [];
while ($row = $res->fetch_assoc()) {
    $replies[] = $row;
}

echo json_encode($replies);
?>