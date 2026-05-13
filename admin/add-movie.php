<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

function normalizeYouTubeUrl($url) {
    // Normalize YouTube short URLs to full URLs
    if (preg_match('#^https?://youtu\.be/([a-zA-Z0-9_-]+)$#', $url, $matches)) {
        return 'https://www.youtube.com/watch?v=' . $matches[1];
    }
    return $url;
}

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
    // Redirect to avoid resubmission and show updated list
    $_SESSION['message'] = $message;
    redirect('movies.php');
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
        redirect("add-movie.php?action=edit&id=$id");
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
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= $movie ? htmlspecialchars($movie['title']) : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required><?= $movie ? htmlspecialchars($movie['description']) : '' ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="year" class="form-label">Year</label>
                                        <input type="number" class="form-control" id="year" name="year" min="1900" max="<?= date('Y') ?>" value="<?= $movie ? $movie['year'] : date('Y') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g. 2h 15m" value="<?= $movie ? htmlspecialchars($movie['duration']) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="trailer_url" class="form-label">Trailer URL (YouTube)</label>
                                <div class="input-group">
                                    <input type="url" class="form-control" id="trailer_url" name="trailer_url" value="<?= $movie ? htmlspecialchars($movie['trailer_url']) : '' ?>">
                                    <?php if ($movie && !empty($movie['trailer_url'])): ?>
                                    <a href="<?= htmlspecialchars($movie['trailer_url']) ?>" target="_blank" class="btn btn-outline-secondary" title="View Trailer">
                                        <i class="bi bi-play-circle"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= $movie && $movie['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="poster" class="form-label">Poster Image</label>
                                <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                                <?php if ($movie && !empty($movie['poster'])): ?>
                                <div class="mt-2">
                                    <img src="../<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="Current Poster" class="img-thumbnail" style="max-height: 200px;">
                                    <p class="small text-muted mt-1">Current poster</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="video" class="form-label">Movie File</label>
                                <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                <?php if ($movie && !empty($movie['video_url'])): ?>
                                <div class="mt-2">
                                    <p class="small">Current file: <?= htmlspecialchars($movie['video_url']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Movie</button>
                    <a href="movies.php" class="btn btn-secondary">Cancel</a>
                </form>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
