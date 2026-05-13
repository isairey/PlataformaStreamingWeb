<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$movies = getMovies(12); // Get latest 12 movies
$categories = getCategories();
$slideshow = getSlideshowItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinemax - Stream Movies Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/slideshow.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="assets/logo/logo.jpg" />
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
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="new-releases.php">New Releases</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Trending</a></li>
                    </ul>
                    
                    <form class="d-flex position-relative">
                        <input class="form-control search-input" type="search" placeholder="Search movies...">
                        <div class="search-results position-absolute top-100 start-0 end-0 bg-dark mt-1 rounded shadow-lg d-none"></div>
                    </form>
                </div>
                
            </div>
        </nav>
    </header>

    <!-- Hero Slider -->
    <!--
    <section class="hero-slider">
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php foreach ($slideshow as $slide): ?>
                <div class="swiper-slide">
                    <div class="slide-bg" style="background-image: url('<?= UPLOAD_DIR . 'slides/' . $slide['image_url'] ?>')"></div>
                    <div class="slide-content">
                        <h1><?= htmlspecialchars($slide['headline']) ?></h1>
                        <p><?= htmlspecialchars($slide['subheadline']) ?></p>
                        <div class="slide-buttons">
                            <a href="watch.php?id=<?= $slide['movie_id'] ?>" class="btn btn-primary btn-lg">Watch Now</a>
                            <?php if (!empty($slide['trailer_url'])): ?>
                            <button class="btn btn-outline-light btn-lg trailer-btn" data-trailer="<?= getYouTubeEmbedUrl($slide['trailer_url']) ?>">Watch Trailer</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    -->
    <section class="carousel">

        <div class="list">

            <?php foreach ($slideshow as $slide): ?>
            <div class="item" style="background-image: url('<?= UPLOAD_DIR . 'slides/' . htmlspecialchars($slide['image_url']) ?>');">
                <div class="content">
                    <!-- Removed the title div as requested -->
                    <div class="name"><?= htmlspecialchars($slide['headline']) ?></div>
                    <div class="des"><?= htmlspecialchars($slide['subheadline']) ?></div>
<div class="btn">
    <a href="watch.php?id=<?= htmlspecialchars($slide['movie_id']) ?>" class="btn-link">
        <button>Watch</button>
    </a>
    <button>Trailer</button>
</div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!--next prev button-->
        <div class="arrows">
            <button class="prev"><</button>
            <button class="next">></button>
        </div>

        <!-- time running -->
        <div class="timeRunning"></div>

    </section>

    <!-- Movie Categories -->
    <section class="movie-categories">
        <div class="container">
            <!-- Featured Movies -->
            <div class="category-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2>Featured Movies</h2>
                    <a href="categories.php" class="btn btn-sm btn-outline-light">View More</a>
                </div>
                <div class="swiper featured-movies-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach (array_slice($movies, 0, 6) as $movie): ?>
                        <div class="swiper-slide movie-card">
                            <a href="watch.php?id=<?= $movie['id'] ?>">
                                <div class="movie-poster">
                                    <img src="<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    <div class="overlay">
                                        <span class="play-icon"><i class="fas fa-play"></i></span>
                                    </div>
                                </div>
                                <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <!-- By Categories -->
            <?php foreach (array_slice($categories, 0, 3) as $category): ?>
            <div class="category-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2><?= htmlspecialchars($category['name']) ?></h2>
                    <a href="categories.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
                <div class="swiper category-movies-swiper" id="category-swiper-<?= $category['id'] ?>">
                    <div class="swiper-wrapper">
                        <?php 
                        $categoryMovies = getMovies(6, $category['id']);
                        foreach ($categoryMovies as $movie): 
                        ?>
                        <div class="swiper-slide movie-card">
                            <a href="watch.php?id=<?= $movie['id'] ?>">
                                <div class="movie-poster">
                                    <img src="<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
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
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Trailer Modal -->
    <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Movie Trailer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe id="trailerIframe" src="" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-links">
                <a href="#">Home</a>
                <a href="#">Movies</a>
                <a href="#">TV Shows</a>
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Privacy Policy</a>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Cinemax. All rights reserved.
            </div>
        </div>
        
<a href="https://wa.me/250798388890" class="whatsapp-btn" target="_blank" title="Chat on WhatsApp">
  <i class="bi bi-whatsapp"></i>
</a>
<style>
    .whatsapp-btn {
  position: fixed;
  bottom: 20px;
  height: 60px;
    width: 60px;
  right: 30px;
  background-color: #25D366;
  color: white;
  font-size: 28px;
  padding: 10px;
  border-radius: 50%;
  z-index: 9999;
  text-align: center;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
  animation: pulse 1.5s infinite;
  transition: transform 0.3s ease;
}
.whatsapp-btn:hover {
  transform: scale(1.1);
  text-decoration: none;
  color: white;
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
  }
  70% {
    box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
  }
}
</style>
    </footer>

       
    </header>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/slideshow.js"></script>
</body>
</html>
