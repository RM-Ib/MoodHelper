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

$user_id = (int) $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';

$where_clause = "WHERE user_id = ?";
$params = [$user_id];
$types = "i";

if ($filter === 'week') {
    $where_clause .= " AND entry_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($filter === 'month') {
    $where_clause .= " AND MONTH(entry_date) = MONTH(CURDATE()) AND YEAR(entry_date) = YEAR(CURDATE())";
}

$query = "
    SELECT entry_id, title, content, mood, entry_date, created_at
    FROM diaryentries
    $where_clause
    ORDER BY entry_date DESC, created_at DESC
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$entries = [];

while ($row = $result->fetch_assoc()) {
    $entries[] = [
        'entry_id' => $row['entry_id'],
        'title' => decryptDiaryText($row['title']),
        'content' => decryptDiaryText($row['content']),
        'mood' => $row['mood'],
        'entry_date' => date('F j, Y', strtotime($row['entry_date'])),
        'created_at' => $row['created_at']
    ];
}

$stmt->close();

$total_stmt = $conn->prepare("
    SELECT COUNT(*) AS total_entries
    FROM diaryentries
    WHERE user_id = ?
");
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_entries = (int) ($total_row['total_entries'] ?? 0);
$total_stmt->close();

$month_stmt = $conn->prepare("
    SELECT COUNT(*) AS this_month
    FROM diaryentries
    WHERE user_id = ?
      AND MONTH(entry_date) = MONTH(CURDATE())
      AND YEAR(entry_date) = YEAR(CURDATE())
");
$month_stmt->bind_param("i", $user_id);
$month_stmt->execute();
$month_result = $month_stmt->get_result();
$month_row = $month_result->fetch_assoc();
$this_month = (int) ($month_row['this_month'] ?? 0);
$month_stmt->close();

$dates_stmt = $conn->prepare("
    SELECT DISTINCT entry_date
    FROM diaryentries
    WHERE user_id = ?
    ORDER BY entry_date DESC
");
$dates_stmt->bind_param("i", $user_id);
$dates_stmt->execute();
$dates_result = $dates_stmt->get_result();

$dates = [];
while ($row = $dates_result->fetch_assoc()) {
    $dates[] = $row['entry_date'];
}
$dates_stmt->close();

$streak = 0;

if (!empty($dates)) {
    $today = new DateTime();
    $yesterday = new DateTime();
    $yesterday->modify('-1 day');

    $first_entry_date = new DateTime($dates[0]);

    if (
        $first_entry_date->format('Y-m-d') === $today->format('Y-m-d') ||
        $first_entry_date->format('Y-m-d') === $yesterday->format('Y-m-d')
    ) {
        $expected = clone $first_entry_date;

        foreach ($dates as $date) {
            $current = new DateTime($date);

            if ($current->format('Y-m-d') === $expected->format('Y-m-d')) {
                $streak++;
                $expected->modify('-1 day');
            } else {
                break;
            }
        }
    }
}

echo json_encode([
    'status' => 'success',
    'entries' => $entries,
    'stats' => [
        'total_entries' => $total_entries,
        'current_streak' => $streak,
        'this_month' => $this_month
    ]
]);

$conn->close();
?>