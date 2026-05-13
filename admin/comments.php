<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

// Handle actions
if ($action === 'delete' && $id > 0) {
    $db->query("DELETE FROM comments WHERE id = $id");
    $message = 'Comment deleted successfully';
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Get total number of comments for pagination
$totalResult = $db->query("SELECT COUNT(*) as total FROM comments");
$totalRow = $totalResult->fetch_assoc();
$totalComments = $totalRow['total'];
$totalPages = ceil($totalComments / $limit);

// Get comments with movie info with limit and offset
$sql = "SELECT c.*, m.title as movie_title FROM comments c LEFT JOIN movies m ON c.movie_id = m.id ORDER BY c.created_at DESC LIMIT $limit OFFSET $offset";
$result = $db->query($sql);
$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

// Display message if set
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments | Cinemax Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/admin-sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Comments</h1>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Movie</th>
                                <th>User</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td><?= $comment['id'] ?></td>
                                <td><?= htmlspecialchars($comment['movie_title']) ?></td>
                                <td><?= htmlspecialchars($comment['user_name']) ?></td>
                                <td><?= nl2br(htmlspecialchars(substr($comment['comment'], 0, 50))) ?><?= strlen($comment['comment']) > 50 ? '...' : '' ?></td>
                                <td><?= date('M d, Y', strtotime($comment['created_at'])) ?></td>
                                <td>
                                    <a href="comments.php?action=delete&id=<?= $comment['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">Page <?= $page ?> of <?= $totalPages ?></a>
                            </li>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
