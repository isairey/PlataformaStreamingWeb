<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['q'])) {
    die();
}

$searchQuery = sanitizeInput($_GET['q']);
$movies = getMovies(10, null, $searchQuery);

if (!empty($movies)) {
    foreach ($movies as $movie) {
        echo '<div class="search-result-item p-2 border-bottom">';
        echo '<a href="watch.php?id=' . $movie['id'] . '" class="d-flex align-items-center text-decoration-none text-white">';
        echo '<img src="' . UPLOAD_DIR . 'posters/' . $movie['poster'] . '" alt="' . htmlspecialchars($movie['title']) . '" width="50" height="75" class="me-3">';
        echo '<div>';
        echo '<h6 class="mb-0">' . htmlspecialchars($movie['title']) . '</h6>';
        echo '<small class="text-muted">' . htmlspecialchars($movie['category_name']) . ' • ' . $movie['year'] . '</small>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo '<div class="p-2 text-center">No results found</div>';
}
?>