<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$movieId = (int)$_GET['id'];
$movie = getMovieById($movieId);

if (!$movie) {
    header("Location: index.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $userName = sanitizeInput($_POST['user_name']);
    $comment = sanitizeInput($_POST['comment']);
    
    if (!empty($userName) && !empty($comment)) {
        addComment($movieId, $userName, $comment);
        // Redirect to refresh the page and show new comment
        header("Location: watch.php?id=" . $movieId);
        exit();
    }
}

$initialCommentsLimit = 5;
$comments = getCommentsByMovieId($movieId);
$totalCommentsCount = count($comments);
$comments = array_slice($comments, 0, $initialCommentsLimit);
$otherMovies = getOtherMovies($movieId, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?> | Cinemax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
    <main class="movie-page">
        <div class="container">
            <div class="movie-player">
                <video controls poster="<?= UPLOAD_DIR . 'posters/' . $movie['poster'] ?>" class="w-100">
                    <source src="<?= UPLOAD_DIR . 'movies/' . $movie['video_url'] ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            
            <div class="movie-actions">
                <a href="<?= UPLOAD_DIR . 'movies/' . $movie['video_url'] ?>" class="btn btn-primary" download>Download Movie</a>
                <?php if (!empty($movie['trailer_url'])): ?>
<button class="btn btn-danger trailer-btn" data-trailer="<?= getYouTubeEmbedUrl($movie['trailer_url']) ?>">
    Watch Trailer
</button>
<?php endif; ?>
            </div>
            
            <div class="movie-details">
                <h1><?= htmlspecialchars($movie['title']) ?></h1>
                <div class="meta">
                    <?php if ($movie['year']): ?><span class="year"><?= $movie['year'] ?></span><?php endif; ?>
                    <?php if ($movie['category_name']): ?><span class="category"><?= htmlspecialchars($movie['category_name']) ?></span><?php endif; ?>
                    <?php if ($movie['duration']): ?><span class="duration"><?= $movie['duration'] ?></span><?php endif; ?>
                </div>
                <div class="description">
                    <?= nl2br(htmlspecialchars($movie['description'])) ?>
                </div>
            </div>

            <div class="other-movies-section mt-5">
                <h2>Other Movies</h2>
                <div class="other-movies-scroll-container">
                    <?php foreach ($otherMovies as $otherMovie): ?>
                    <div class="swiper-slide movie-card">
                        <a href="watch.php?id=<?= $otherMovie['id'] ?>">
                            <div class="movie-poster">
                                <img src="<?= UPLOAD_DIR . 'posters/' . htmlspecialchars($otherMovie['poster']) ?>" alt="<?= htmlspecialchars($otherMovie['title']) ?>">
                                <div class="overlay">
                                    <span class="play-icon"><i class="fas fa-play"></i></span>
                                </div>
                            </div>
                            <h3 class="movie-title"><?= htmlspecialchars($otherMovie['title']) ?></h3>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="comments-section">
                <h2>Comments (<?= $totalCommentsCount ?>)</h2>
                <div class="comments-list scrollable-comments" id="comments-list">
                    <?php foreach ($comments as $comment): ?>
                    <div class="comment card p-3 mb-3 bg-dark rounded">
                        <div class="comment-header d-flex justify-content-between mb-2">
                            <span class="user-name fw-bold"><?= htmlspecialchars($comment['user_name']) ?></span>
                            <span class="comment-date text-muted" data-timestamp="<?= htmlspecialchars($comment['created_at']) ?>"><?= date('M d, Y H:i', strtotime($comment['created_at'])) ?></span>
                        </div>
                        <div class="comment-text"><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($comments)): ?>
                    <p>No comments yet. Be the first to comment!</p>
                    <?php endif; ?>
                </div>
                <?php if ($totalCommentsCount > $initialCommentsLimit): ?>
                <button id="load-more-comments" class="btn btn-secondary mb-3">Load More Comments</button>
                <?php endif; ?>
                <form class="comment-form mt-4" method="POST">
                    <input type="hidden" name="movie_id" value="<?= $movieId ?>">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="user_name" placeholder="Your Name" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Your Comment" required></textarea>
                    </div>
                    <button type="submit" name="submit_comment" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Trailer Modal -->
   <!-- Trailer Modal -->
<div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($movie['title']) ?> Trailer</h5>
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
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= WHATSAPP_TEXT ?>" class="whatsapp-button" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/load-more-comments.js"></script>
    <script>
    // Trailer modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const trailerModal = new bootstrap.Modal(document.getElementById('trailerModal'));
        const trailerIframe = document.getElementById('trailerIframe');
        
        // Handle trailer button click
        const trailerBtn = document.querySelector('.trailer-btn');
        if (trailerBtn) {
            trailerBtn.addEventListener('click', function() {
                const trailerUrl = this.getAttribute('data-trailer');
                if (trailerUrl) {
                    // Set the iframe source and show modal
                    trailerIframe.src = trailerUrl;
                    trailerModal.show();
                    
                    // Update modal title with movie title
                    document.querySelector('#trailerModal .modal-title').textContent = 
                        '<?= htmlspecialchars($movie['title']) ?> Trailer';
                }
            });
        }
        
        // Reset iframe when modal is closed to stop video playback
        document.getElementById('trailerModal').addEventListener('hidden.bs.modal', function() {
            trailerIframe.src = '';
        });
    });
</script>
</body>
</html>
