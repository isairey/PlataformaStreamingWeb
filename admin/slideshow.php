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
    // First get the image filename to delete it
    $result = $db->query("SELECT image_url FROM slideshow WHERE id = $id");
    if ($result->num_rows > 0) {
        $slide = $result->fetch_assoc();
        $imagePath = '../' . UPLOAD_DIR . 'slides/' . $slide['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    $db->query("DELETE FROM slideshow WHERE id = $id");
    $message = 'Slide deleted successfully';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline = sanitizeInput($_POST['headline']);
    $subheadline = sanitizeInput($_POST['subheadline']);
    $movieId = (int)$_POST['movie_id'];
    
    if ($id > 0) {
        // Update existing slide
        $sql = "UPDATE slideshow SET 
                headline = '$headline', 
                subheadline = '$subheadline', 
                movie_id = $movieId 
                WHERE id = $id";
        $db->query($sql);
        $message = 'Slide updated successfully';
    } else {
        // Insert new slide
        $sql = "INSERT INTO slideshow (headline, subheadline, movie_id) 
                VALUES ('$headline', '$subheadline', $movieId)";
        $db->query($sql);
        $id = $db->getLastInsertId();
        $message = 'Slide added successfully';
    }
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], '../' . UPLOAD_DIR . 'slides/');
        if ($upload['success']) {
            // Delete old image if exists
            if ($id > 0) {
                $result = $db->query("SELECT image_url FROM slideshow WHERE id = $id");
                if ($result->num_rows > 0) {
                    $oldSlide = $result->fetch_assoc();
                    $oldImagePath = '../' . UPLOAD_DIR . 'slides/' . $oldSlide['image_url'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            
            $db->query("UPDATE slideshow SET image_url = '{$upload['filename']}' WHERE id = $id");
        } else {
            $message .= ' (but image upload failed: ' . $upload['message'] . ')';
        }
    }
    
    // Redirect to avoid form resubmission
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        redirect("slideshow.php?action=edit&id=$id");
    }
}

// Get slide data if editing
$slide = null;
if ($id > 0 && ($action === 'edit' || $_SERVER['REQUEST_METHOD'] === 'POST')) {
    $result = $db->query("SELECT * FROM slideshow WHERE id = $id");
    $slide = $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Get all slides
$slides = getSlideshowItems();

// Get movies for dropdown
$movies = getMovies();

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
    <title><?= $action === 'edit' ? 'Edit' : 'Add' ?> Slide | Cinemax Admin</title>
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
                    <h1 class="h2">Slideshow</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                        <i class="bi bi-plus-lg"></i> Add Slide
                    </button>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Headline</th>
                                <th>Movie</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($slides as $sl): ?>
                            <tr>
                                <td><?= $sl['id'] ?></td>
                                <td>
                                    <?php if (!empty($sl['image_url'])): ?>
                                    <img src="../<?= UPLOAD_DIR . 'slides/' . $sl['image_url'] ?>" alt="Slide Image" style="max-width: 100px;">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($sl['headline']) ?></td>
                                <td><?= htmlspecialchars($sl['movie_title']) ?></td>
                                <td>
                                    <a href="slideshow.php?action=edit&id=<?= $sl['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="slideshow.php?action=delete&id=<?= $sl['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add/Edit Slide Modal -->
    <div class="modal fade" id="addSlideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $action === 'edit' ? 'Edit' : 'Add' ?> Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="headline" class="form-label">Headline</label>
                                    <input type="text" class="form-control" id="headline" name="headline" value="<?= $slide ? htmlspecialchars($slide['headline']) : '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subheadline" class="form-label">Subheadline</label>
                                    <input type="text" class="form-control" id="subheadline" name="subheadline" value="<?= $slide ? htmlspecialchars($slide['subheadline']) : '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="movie_id" class="form-label">Linked Movie</label>
                                    <select class="form-select" id="movie_id" name="movie_id">
                                        <option value="">None</option>
                                        <?php foreach ($movies as $movie): ?>
                                        <option value="<?= $movie['id'] ?>" <?= $slide && $slide['movie_id'] == $movie['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($movie['title']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Slide Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <?php if ($slide && !empty($slide['image_url'])): ?>
                                    <div class="mt-2">
                                        <img src="../<?= UPLOAD_DIR . 'slides/' . $slide['image_url'] ?>" alt="Current Slide" class="img-thumbnail" style="max-height: 200px;">
                                        <p class="small text-muted mt-1">Current image</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($action === 'edit'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('addSlideModal'));
            modal.show();
        });
        <?php endif; ?>
    </script>
</body>
</html>
