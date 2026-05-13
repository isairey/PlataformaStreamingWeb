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
    $db->query("DELETE FROM categories WHERE id = $id");
    $message = 'Category deleted successfully';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    
    if ($id > 0) {
        // Update existing category
        $sql = "UPDATE categories SET name = '$name' WHERE id = $id";
        $db->query($sql);
        $message = 'Category updated successfully';
    } else {
        // Insert new category
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        $db->query($sql);
        $message = 'Category added successfully';
    }
    
    // Redirect to avoid form resubmission
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        redirect("categories.php");
    }
}

// Get category data if editing
$category = null;
if ($id > 0 && $action === 'edit') {
    $result = $db->query("SELECT * FROM categories WHERE id = $id");
    $category = $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Get total number of categories for pagination
$totalResult = $db->query("SELECT COUNT(*) as total FROM categories");
$totalRow = $totalResult->fetch_assoc();
$totalCategories = $totalRow['total'];
$totalPages = ceil($totalCategories / $limit);

// Get categories with limit and offset
$result = $db->query("SELECT * FROM categories ORDER BY id DESC LIMIT $limit OFFSET $offset");
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
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
    <title>Categories | Cinemax Admin</title>
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
                    <h1 class="h2">Categories</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg"></i> Add Category
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
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td><?= htmlspecialchars($cat['name']) ?></td>
                                <td>
                                    <a href="categories.php?action=edit&id=<?= $cat['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="categories.php?action=delete&id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
    
    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $action === 'edit' ? 'Edit' : 'Add' ?> Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $category ? htmlspecialchars($category['name']) : '' ?>" required>
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
            var modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
            modal.show();
        });
        <?php endif; ?>
    </script>
</body>
</html>
