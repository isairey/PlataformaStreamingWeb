<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get recently added movies (sorted by creation date, newest first)
$movies = getMovies(20, null, null, 'newest');
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Releases | Cinemax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/logo/cinemax.png" />
    <style>
        .new-releases-header {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url('assets/images/new-releases-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 5rem 0;
            margin-bottom: 3rem;
            text-align: center;
            color: white;
        }
        
        .new-releases-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .new-releases-header p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .new-movie-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .new-movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }
        
        .new-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #E50914;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            z-index: 2;
        }
        
        .movie-poster-container {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2/3;
        }
        
        .movie-poster-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .new-movie-card:hover .movie-poster-container img {
            transform: scale(1.05);
        }
        
        .movie-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            padding: 1.5rem;
            color: white;
        }
        
        .movie-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .movie-meta {
            display: flex;
            justify-content: space-between;
            color: #B3B3B3;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .movie-actions {
            display: flex;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .new-releases-header {
                padding: 3rem 0;
            }
            
            .new-releases-header h1 {
                font-size: 2rem;
            }
            
            .movie-title {
                font-size: 1.2rem;
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
                    <img src="assets/logo/cinemax.png" alt="Cinemax Logo" style="height: 60px; object-fit: contain; background-color: white; border-radius: 12px; padding: 4px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link active" href="new-releases.php">New Releases</a></li>
                        <li class="nav-item"><a class="nav-link" href="">Trending</a></li>
                    </ul>
                    
                    <form class="d-flex position-relative">
                        <input class="form-control search-input" type="search" placeholder="Search movies...">
                        <div class="search-results position-absolute top-100 start-0 end-0 bg-dark mt-1 rounded shadow-lg d-none"></div>
                    </form>
                </div>
                
            </div>
        </nav>
    </header>
    <!-- Hero Section -->
    <section class="new-releases-header">
        <div class="container">
            <h1>New Releases</h1>
            <p>Discover the latest movies we've added to our collection. Fresh content updated regularly for your viewing pleasure.</p>
        </div>
    </section>

    <!-- New Releases Grid -->
    <section class="new-releases-grid">
        <div class="container">
            <div class="row">
                <?php foreach ($movies as $movie): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="new-movie-card">
                        <?php if (isNewRelease($movie['created_at'])): ?>
                        <span class="new-badge">NEW</span>
                        <?php endif; ?>
                        <div class="movie-poster-container">
                            <img src="<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                        </div>
                        <div class="movie-overlay">
                            <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                            <div class="movie-meta">
                                <span><?= $movie['year'] ?></span>
                                <span><?= htmlspecialchars($movie['category_name']) ?></span>
                            </div>
                            <div class="movie-actions">
                                <a href="watch.php?id=<?= $movie['id'] ?>" class="btn btn-sm btn-primary">Watch Now</a>
                                <?php if (!empty($movie['trailer_url'])): ?>
                                <button class="btn btn-sm btn-outline-light trailer-btn" data-trailer="<?= getYouTubeEmbedUrl($movie['trailer_url']) ?>">
                                    Trailer
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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
                <a href="index.php">Home</a>
                <a href="categories.php">Categories</a>
                <a href="new-releases.php">New Releases</a>
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
    <script>
        // Trailer modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const trailerModal = new bootstrap.Modal(document.getElementById('trailerModal'));
            const trailerIframe = document.getElementById('trailerIframe');
            
            document.querySelectorAll('.trailer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const trailerUrl = this.getAttribute('data-trailer');
                    if (trailerUrl) {
                        trailerIframe.src = trailerUrl;
                        trailerModal.show();
                    }
                });
            });
            
            // Close modal and stop video when modal is hidden
            document.getElementById('trailerModal').addEventListener('hidden.bs.modal', function() {
                trailerIframe.src = '';
            });
        });
    </script>
</body>
</html>