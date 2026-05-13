<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$movies = getTrendingMovies(12);
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cinemax - Trending Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/slideshow.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="icon" type="image/png" href="assets/logo/cinemax.png" />
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/logo/cinemax.png" alt="Cinemax Logo" style="height: 60px; object-fit: contain; background-color: white; border-radius: 12px; padding: 4px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="new-releases.php">New Releases</a></li>
                        <li class="nav-item"><a class="nav-link active" href="trending.php">Trending</a></li>
                    </ul>
                    
                    <form class="d-flex position-relative">
                        <input class="form-control search-input" type="search" placeholder="Search movies...">
                        <div class="search-results position-absolute top-100 start-0 end-0 bg-dark mt-1 rounded shadow-lg d-none"></div>
                    </form>
                </div>
                
            </div>
        </nav>
    </header>

    <section class="movie-categories">
        <div class="container">
            <div class="category-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2>Trending Movies</h2>
                    <a href="categories.php" class="btn btn-sm btn-outline-light">View Categories</a>
                </div>
                <div class="swiper featured-movies-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($movies as $movie): ?>
                        <div class="swiper-slide movie-card">
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
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
