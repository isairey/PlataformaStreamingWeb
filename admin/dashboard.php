<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();

// Get counts
$moviesCount = $db->query("SELECT COUNT(*) as count FROM movies")->fetch_assoc()['count'];
$categoriesCount = $db->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$commentsCount = $db->query("SELECT COUNT(*) as count FROM comments")->fetch_assoc()['count'];
$slidesCount = $db->query("SELECT COUNT(*) as count FROM slideshow")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | Cinemax Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
            /* Animation for cards and table rows */
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
            .fade-in-left {
                animation: fadeInLeft 0.3s ease forwards;
                opacity: 0;
            }
            .fade-in-left:nth-child(1) { animation-delay: 0.1s; }
            .fade-in-left:nth-child(2) { animation-delay: 0.2s; }
            .fade-in-left:nth-child(3) { animation-delay: 0.3s; }
            .fade-in-left:nth-child(4) { animation-delay: 0.4s; }
            .fade-in-left:nth-child(5) { animation-delay: 0.5s; }
            .fade-in-left:nth-child(6) { animation-delay: 0.6s; }

            /* Corporate Blue Theme for cards */
            .card {
                border-radius: 12px !important;
                background-color: #FFFFFF; /* Pure White */
                border: 1px solid #E0E0E0; /* Light Gray */
                transition: box-shadow 0.3s ease, background-color 0.3s ease;
                color: #2C3E50; /* Dark Blue-Gray */
                box-shadow: none;
            }
            .card:hover {
                box-shadow: 0 4px 20px rgba(52, 152, 219, 0.2); /* Light blue shadow */
                background-color: #f9fbfd;
                transform: translateY(-4px);
            }
            .card .card-title {
                color: #2C3E50; /* Dark Blue-Gray */
                font-weight: 700;
            }
            .card .card-text {
                color: #2C3E50;
            }
            .card .btn {
                background-color: #3498DB; /* Vibrant Blue */
                color: white;
                border: none;
                border-radius: 8px !important;
                box-shadow: 0 2px 6px rgba(52, 152, 219, 0.4);
                transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
                font-weight: 600;
                padding: 0.375rem 0.75rem;
            }
            .card .btn:hover {
                background-color: #2980B9; /* Darker Blue */
                box-shadow: 0 6px 16px rgba(52, 152, 219, 0.6);
                transform: translateY(-2px);
            }
            .card .btn:active {
                background-color: #2471A3;
                transform: translateY(0);
                box-shadow: 0 2px 6px rgba(52, 152, 219, 0.4);
            }

            /* Style View All buttons like movie card buttons */
            .card .btn {
                border-radius: 8px !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
                font-weight: 600;
                padding: 0.375rem 0.75rem;
            }
            .card .btn:hover {
                background-color: #3a3a3a !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                transform: translateY(-2px);
            }
            .card .btn:active {
                transform: translateY(0);
                box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            }

            /* Redesigned modern table styles */
            table {
                width: 100%;
                border-collapse: separate !important;
                border-spacing: 0 12px !important;
                border-radius: 12px;
                overflow: hidden;
                background-color: #f9fbfd;
                box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            thead tr {
                background-color: #3498DB;
                color: white;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: none;
            }
            thead th {
                padding: 12px 15px;
                border: none;
            }
            tbody tr {
                background-color: white;
                transition: background-color 0.3s ease, box-shadow 0.3s ease;
                border-radius: 12px;
                box-shadow: 0 2px 6px rgba(52, 152, 219, 0.1);
            }
            tbody tr:hover {
                background-color: #e6f0fb;
                box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
            }
            tbody tr td {
                border: none !important;
                vertical-align: middle;
                padding: 12px 15px;
                color: #2C3E50;
            }

            /* Sidebar layering and transition */
            .sidebar {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                z-index: 100;
                box-shadow: 2px 0 12px rgba(0,0,0,0.2);
            }
            .sidebar.open {
                z-index: 105;
                box-shadow: 4px 0 24px rgba(0,0,0,0.3);
            }
            .sidebar-overlay {
                z-index: 104;
            }

            /* Button styles inspired by Windows 11 */
            .btn {
                border-radius: 8px !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
                font-weight: 600;
            }
            .btn:hover {
                background-color: #3a3a3a !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                transform: translateY(-2px);
            }
            .btn:active {
                transform: translateY(0);
                box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            }
        </style>
    </head>
    <body>
        <?php include 'includes/admin-header.php'; ?>
        
        <div class="container-fluid">
            <div class="row">
                <?php include 'includes/admin-sidebar.php'; ?>
                
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 fade-in-left">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Movies</h5>
                                    <p class="card-text display-4"><?= $moviesCount ?></p>
                                    <a href="movies.php" class="btn btn-sm btn-primary float-end">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 fade-in-left">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Categories</h5>
                                    <p class="card-text display-4"><?= $categoriesCount ?></p>
                                    <a href="categories.php" class="btn btn-sm btn-success float-end">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 fade-in-left">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Comments</h5>
                                    <p class="card-text display-4"><?= $commentsCount ?></p>
                                    <a href="comments.php" class="btn btn-sm btn-warning float-end">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 fade-in-left">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Slides</h5>
                                    <p class="card-text display-4"><?= $slidesCount ?></p>
                                    <a href="slideshow.php" class="btn btn-sm btn-danger float-end">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="mt-4">Recent Movies</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fade-in-left">
                                <?php
                                $movies = getMovies(5);
                                foreach ($movies as $movie):
                                ?>
                                <tr>
                                    <td><?= $movie['id'] ?></td>
                                    <td><?= htmlspecialchars($movie['title']) ?></td>
                                    <td><?= htmlspecialchars($movie['category_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($movie['created_at'])) ?></td>
                                    <td>
                                        <a href="movies.php?action=edit&id=<?= $movie['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="movies.php?action=delete&id=<?= $movie['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
