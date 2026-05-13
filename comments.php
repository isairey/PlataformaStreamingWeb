<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$movieId = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

if ($movieId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID']);
    exit();
}

$db = new Database();
$sql = "SELECT * FROM comments WHERE movie_id = $movieId ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $db->query($sql);

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'id' => $row['id'],
            'user_name' => htmlspecialchars($row['user_name']),
            'comment' => nl2br(htmlspecialchars($row['comment'])),
            'created_at' => $row['created_at']
        ];
    }
}

echo json_encode(['success' => true, 'comments' => $comments]);
exit();
?>
