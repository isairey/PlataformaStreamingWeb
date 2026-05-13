<?php
require_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function sanitizeInput($data) {
    $db = new Database();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $db->escape($data);
}

function uploadFile($file, $targetDir) {
    $fileName = basename($file['name']);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    
    // Generate unique filename
    $newFileName = uniqid() . '.' . $fileType;
    $newTargetPath = rtrim($targetDir, '/') . '/' . $newFileName;
    
    // Check if file is valid
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm'];
    if (!in_array(strtolower($fileType), $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type.'];
    }
    
    // Check file size (e.g., 50MB max)
    if ($file['size'] > 50000000) {
        return ['success' => false, 'message' => 'File is too large.'];
    }
    
    // Ensure target directory exists
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create target directory.'];
        }
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $newTargetPath)) {
        return ['success' => true, 'filename' => $newFileName];
    } else {
        return ['success' => false, 'message' => 'Error uploading file.'];
    }
}

function getYouTubeEmbedUrl($url) {
    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : false;
}

function getMovies($limit = null, $category = null, $search = null, $sort = 'newest') {
    $db = new Database();
    $sql = "SELECT m.*, c.name as category_name FROM movies m LEFT JOIN categories c ON m.category_id = c.id";
    
    $where = [];
    if ($category) {
        $where[] = "m.category_id = " . (int)$category;
    }
    if ($search) {
        $search = $db->escape($search);
        $where[] = "(m.title LIKE '%$search%' OR m.description LIKE '%$search%' OR c.name LIKE '%$search%')";
    }
    
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    
    if ($sort === 'newest') {
        $sql .= " ORDER BY m.created_at DESC";
    } else {
        $sql .= " ORDER BY m.created_at DESC"; // default fallback
    }
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $result = $db->query($sql);
    $movies = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    }
    
    return $movies;
}

function getTrendingMovies($limit = 12) {
    $db = new Database();
    $sql = "SELECT m.*, c.name as category_name FROM movies m LEFT JOIN categories c ON m.category_id = c.id ORDER BY m.visit_count DESC LIMIT " . (int)$limit;
    $result = $db->query($sql);
    $movies = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    }
    
    return $movies;
}

/**
 * Check if a movie is a new release (added within last 30 days)
 *
 * @param string $createdAt Movie creation date
 * @return bool
 */
function isNewRelease($createdAt) {
    $createdDate = new DateTime($createdAt);
    $now = new DateTime();
    $interval = $now->diff($createdDate);
    return $interval->days <= 30;
}

function getMovieById($id) {
    $db = new Database();
    $id = (int)$id;
    $sql = "SELECT m.*, c.name as category_name FROM movies m LEFT JOIN categories c ON m.category_id = c.id WHERE m.id = $id";
    $result = $db->query($sql);
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function getCategories() {
    $db = new Database();
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = $db->query($sql);
    $categories = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    
    return $categories;
}

function getCategoryById($id) {
    $db = new Database();
    $id = (int)$id;
    $sql = "SELECT * FROM categories WHERE id = $id";
    $result = $db->query($sql);
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function getSlideshowItems() {
    $db = new Database();
    $sql = "SELECT s.*, m.title as movie_title FROM slideshow s LEFT JOIN movies m ON s.movie_id = m.id ORDER BY s.created_at DESC";
    $result = $db->query($sql);
    $slides = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $slides[] = $row;
        }
    }
    
    return $slides;
}

function getSlideById($id) {
    $db = new Database();
    $id = (int)$id;
    $sql = "SELECT * FROM slideshow WHERE id = $id";
    $result = $db->query($sql);
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function getCommentsByMovieId($movieId) {
    $db = new Database();
    $movieId = (int)$movieId;
    $sql = "SELECT * FROM comments WHERE movie_id = $movieId ORDER BY created_at DESC";
    $result = $db->query($sql);
    $comments = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
    
    return $comments;
}

function addComment($movieId, $userName, $comment) {
    $db = new Database();
    $movieId = (int)$movieId;
    $userName = $db->escape($userName);
    $comment = $db->escape($comment);
    
    $sql = "INSERT INTO comments (movie_id, user_name, comment) VALUES ($movieId, '$userName', '$comment')";
    return $db->query($sql);
}

/**
 * Get other movies excluding a specific movie ID
 *
 * @param int $excludeId Movie ID to exclude
 * @param int|null $limit Number of movies to fetch
 * @return array List of movies
 */
function getOtherMovies($excludeId, $limit = null) {
    $db = new Database();
    $excludeId = (int)$excludeId;
    $sql = "SELECT m.*, c.name as category_name FROM movies m LEFT JOIN categories c ON m.category_id = c.id WHERE m.id != $excludeId ORDER BY m.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $result = $db->query($sql);
    $movies = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    }
    
    return $movies;
}

/**
 * Get active sidebar ads ordered by position and created_at
 *
 * @return array List of active sidebar ads
 */
function getActiveSidebarAds() {
    $db = new Database();
    $sql = "SELECT * FROM sidebar_ads WHERE status = 'active' ORDER BY position, created_at DESC";
    $result = $db->query($sql);
    $ads = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ads[] = $row;
        }
    }
    
    return $ads;
}

function getAllSidebarAds() {
    $db = new Database();
    $sql = "SELECT * FROM sidebar_ads ORDER BY position, created_at DESC";
    $result = $db->query($sql);
    $ads = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ads[] = $row;
        }
    }
    return $ads;
}

function getSidebarAdById($id) {
    $db = new Database();
    $id = (int)$id;
    $sql = "SELECT * FROM sidebar_ads WHERE id = $id";
    $result = $db->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function addSidebarAd($title, $imageUrl, $link, $position, $status) {
    $db = new Database();
    $title = $db->escape($title);
    $imageUrl = $db->escape($imageUrl);
    $link = $db->escape($link);
    $position = (int)$position;
    $status = $db->escape($status);

    $sql = "INSERT INTO sidebar_ads (title, image_url, link, position, status, created_at) VALUES ('$title', '$imageUrl', '$link', $position, '$status', NOW())";
    return $db->query($sql);
}

function updateSidebarAd($id, $title, $imageUrl, $link, $position, $status) {
    $db = new Database();
    $id = (int)$id;
    $title = $db->escape($title);
    $imageUrl = $db->escape($imageUrl);
    $link = $db->escape($link);
    $position = (int)$position;
    $status = $db->escape($status);

    $sql = "UPDATE sidebar_ads SET title = '$title', image_url = '$imageUrl', link = '$link', position = $position, status = '$status' WHERE id = $id";
    return $db->query($sql);
}

function deleteSidebarAd($id) {
    $db = new Database();
    $id = (int)$id;
    $sql = "DELETE FROM sidebar_ads WHERE id = $id";
    return $db->query($sql);
}
?>