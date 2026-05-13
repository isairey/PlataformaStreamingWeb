<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$category = $categoryId ? getCategoryById($categoryId) : null;
$searchQuery = isset($_GET['q']) ? sanitizeInput($searchQuery) : null;

if ($categoryId && !$category) {
    header("Location: categories.php");
    exit();
}

$movies = getMovies(null, $categoryId, $searchQuery);
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $category ? htmlspecialchars($category['name']) : 'All Categories' ?> | Cinemax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="icon" type="image/png" href="assets/logo/cinemax.png" />
    <style>
        .categories-list a.category-item {
            color: #e50914;
           padding: 10px 20px;
            border-radius: 8px;
            background-color: #f3f3f3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: inline-block;
            margin: 5px;
            font-size: 16px;
            font-weight: bold;
            position: relative;
            overflow: hidden;
           text-decoration: none;
            animation: fadeInLeft 0.3s ease forwards;
            opacity: 0;
        }
        .categories-list a.category-item:hover,
        .categories-list a.category-item.active {
            color: white !important;
            background-color: #e50914 !important;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.6);
            transform: translateX(5px);
        }
        .categories-list a.category-item:nth-child(1) { animation-delay: 0.1s; }
        .categories-list a.category-item:nth-child(2) { animation-delay: 0.2s; }
        .categories-list a.category-item:nth-child(3) { animation-delay: 0.3s; }
        .categories-list a.category-item:nth-child(4) { animation-delay: 0.4s; }
        .categories-list a.category-item:nth-child(5) { animation-delay: 0.5s; }
        .categories-list a.category-item:nth-child(6) { animation-delay: 0.6s; }
        .categories-list a.category-item:nth-child(7) { animation-delay: 0.7s; }
        .categories-list a.category-item:nth-child(8) { animation-delay: 0.8s; }
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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/logo/cinemax.png" alt="Cinemax Logo" style="height: 60px; object-fit: contain; background-color: white; border-radius: 12px; padding: 4px;" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="new-releases.php">New Releases</a></li>
                        <li class="nav-item"><a class="nav-link" href="trending.php">Trending</a></li>
                    </ul>
                    <form class="d-flex position-relative" action="categories.php" method="GET">
                        <input class="form-control search-input" type="search" name="q" placeholder="Search movies..." value="<?= htmlspecialchars($searchQuery) ?>" />
                        <?php if ($categoryId): ?>
                        <input type="hidden" name="id" value="<?= $categoryId ?>" />
                        <?php endif; ?>
                        <div class="search-results position-absolute top-100 start-0 end-0 bg-dark mt-1 rounded shadow-lg d-none"></div>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="categories-page">
        <div class="container">
            <div class="row g-3">
                <!-- Categories List -->
                <div class="col-12 mb-3">
                    <div class="categories-list d-flex flex-wrap gap-3" style="padding: 10px 0;">
                        <?php foreach ($categories as $cat): ?>
                            <a href="categories.php?id=<?= $cat['id'] ?>" class="category-item <?= $categoryId == $cat['id'] ? 'active' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Movies List -->
                <div class="col-12">
                    <div class="movies-header">
                        <h2><?= $category ? htmlspecialchars($category['name']) : 'All Movies' ?></h2>
                        <?php if ($searchQuery): ?>
                        <p>Search results for: "<?= htmlspecialchars($searchQuery) ?>"</p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($movies)): ?>
                    <div class="movies-grid d-flex flex-wrap gap-3">
                        <?php foreach ($movies as $movie): ?>
                        <div class="movie-card" style="flex: 1 0 21%; max-width: 21%;">
                            <a href="watch.php?id=<?= $movie['id'] ?>">
                                <div class="movie-poster">
                                    <img src="<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" />
                                    <div class="overlay">
                                        <span class="play-icon"><i class="fas fa-play"></i></span>
                                    </div>
                                </div>
                                <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="no-results">
                        <p>No movies found<?= $searchQuery ? ' for "' . htmlspecialchars($searchQuery) . '"' : '' ?>.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="categories.php">Categories</a>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Privacy Policy</a>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Cinemax. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= WHATSAPP_TEXT ?>" class="whatsapp-button" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
