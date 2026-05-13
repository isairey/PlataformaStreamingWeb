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
    $db->query("DELETE FROM movies WHERE id = $id");
    $message = 'Movie deleted successfully';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $categoryId = (int)$_POST['category_id'];
    $year = (int)$_POST['year'];
    $duration = sanitizeInput($_POST['duration']);
    $trailerUrl = sanitizeInput($_POST['trailer_url']);
    
    if ($id > 0) {
        // Update existing movie
        $sql = "UPDATE movies SET 
                title = '$title', 
                description = '$description', 
                category_id = $categoryId, 
                year = $year, 
                duration = '$duration', 
                trailer_url = '$trailerUrl' 
                WHERE id = $id";
        $db->query($sql);
        $message = 'Movie updated successfully';
    } else {
        // Insert new movie
        $sql = "INSERT INTO movies (title, description, category_id, year, duration, trailer_url) 
                VALUES ('$title', '$description', $categoryId, $year, '$duration', '$trailerUrl')";
        $db->query($sql);
        $id = $db->getLastInsertId();
        $message = 'Movie added successfully';
    }
    
    // Handle file uploads
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['poster'], '../' . UPLOAD_DIR . 'posters/');
        if ($upload['success']) {
            $db->query("UPDATE movies SET poster = '{$upload['filename']}' WHERE id = $id");
        } else {
            $message .= ' (but poster upload failed: ' . $upload['message'] . ')';
        }
    }
    
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['video'], '../' . UPLOAD_DIR . 'movies/');
        if ($upload['success']) {
            $db->query("UPDATE movies SET video_url = '{$upload['filename']}' WHERE id = $id");
        } else {
            $message .= ' (but video upload failed: ' . $upload['message'] . ')';
        }
    }
    
    // Redirect to avoid form resubmission
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        redirect("movies.php?action=edit&id=$id");
    }
}

// Get movie data if editing
$movie = null;
if ($id > 0 && ($action === 'edit' || $_SERVER['REQUEST_METHOD'] === 'POST')) {
    $result = $db->query("SELECT * FROM movies WHERE id = $id");
    $movie = $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Get categories
$categories = getCategories();

// Display message if set
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Get total number of movies for pagination
$totalResult = $db->query("SELECT COUNT(*) as total FROM movies");
$totalRow = $totalResult->fetch_assoc();
$totalMovies = $totalRow['total'];
$totalPages = ceil($totalMovies / $limit);

$allMovies = $db->query("SELECT m.*, c.name as category_name 
                         FROM movies m 
                         LEFT JOIN categories c ON m.category_id = c.id 
                         ORDER BY m.id DESC
                         LIMIT $limit OFFSET $offset");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $action === 'edit' ? 'Edit' : 'Add' ?> Movie | Cinemax Admin</title>
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
                    <h1 class="h2"><?= $action === 'edit' ? 'Edit Movie' : 'Add New Movie' ?></h1>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                
                <div class="d-flex mb-3">
                    <a href="add-movie.php" class="btn btn-success me-2">Add New Movie</a>
                    <a href="movies.php" class="btn btn-sidebar nav-link d-flex align-items-center" style="padding: 0.375rem 1rem; border-radius: 0.25rem; height: 38px; font-weight: 500;">
                        <i class="bi bi-eye me-2"></i> View All
                    </a>
                </div>
                <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                <table class="table table-striped table-bordered table-animated">
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allMovies) && $allMovies->num_rows > 0): ?>
                            <?php while ($movie = $allMovies->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($movie['poster'])): ?>
                                        <img src="../<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="Poster" style="max-height: 80px;">
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($movie['title']) ?></td>
                                <td><?= htmlspecialchars($movie['category_name']) ?></td>
                                <td><?= htmlspecialchars($movie['year']) ?></td>
                                <td><?= htmlspecialchars($movie['duration']) ?></td>
                                <td>
                                    <a href="add-movie.php?action=edit&id=<?= $movie['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="movies.php?action=delete&id=<?= $movie['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No movies found.</td></tr>
                        <?php endif; ?>
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
            </main>
        </div>
    </div>

    <style>
        /* Sidebar-like button styles */
        .btn-sidebar {
            color: rgba(255, 255, 255, 0.75);
            background-color: #121212;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }
        .btn-sidebar:hover {
            color: white;
            background-color: rgba(229, 9, 20, 0.2);
            transform: translateX(5px);
        }
        .btn-sidebar:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.5);
        }
        .btn-sidebar i {
            transition: all 0.3s ease;
        }
        .btn-sidebar:hover i {
            color: var(--primary-color);
        }

        /* Animation for table rows */
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .table-animated tbody tr {
            animation: fadeInLeft 0.3s ease forwards;
            opacity: 0;
        }
        .table-animated tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table-animated tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .table-animated tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .table-animated tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .table-animated tbody tr:nth-child(5) { animation-delay: 0.5s; }
        .table-animated tbody tr:nth-child(6) { animation-delay: 0.6s; }

    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
