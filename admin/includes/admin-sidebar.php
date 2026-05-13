 <?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* Admin Sidebar Styles */
.sidebar {
    background-color: #121212;
    color: white;
    height: 100vh;
    position: fixed;
    z-index: 100;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.75);
    padding: 0.75rem 1rem;
    border-radius: 0.25rem;
    margin: 0.25rem 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.sidebar .nav-link:hover {
    color: white;
    background-color: rgba(229, 9, 20, 0.2);
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    color: white;
    background-color: rgba(229, 9, 20, 0.5);
    font-weight: 500;
    border-left: 3px solid var(--primary-color);
}

.sidebar .nav-link i {
    width: 24px;
    text-align: center;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover i {
    color: var(--primary-color);
}

.sidebar .nav-link.active i {
    color: white;
}

.sidebar .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.1);
    margin: 1rem 0.5rem;
}

.sidebar .nav-link.text-danger {
    color: #ff6b6b !important;
}

.sidebar .nav-link.text-danger:hover {
    background-color: rgba(255, 0, 0, 0.1);
}

/* Animation for sidebar items */
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

.sidebar .nav-item {
    animation: fadeInLeft 0.3s ease forwards;
    opacity: 0;
}

.sidebar .nav-item:nth-child(1) { animation-delay: 0.1s; }
.sidebar .nav-item:nth-child(2) { animation-delay: 0.2s; }
.sidebar .nav-item:nth-child(3) { animation-delay: 0.3s; }
.sidebar .nav-item:nth-child(4) { animation-delay: 0.4s; }
.sidebar .nav-item:nth-child(5) { animation-delay: 0.5s; }
.sidebar .nav-item:nth-child(6) { animation-delay: 0.6s; }
.sidebar .nav-item:nth-child(7) { animation-delay: 0.7s; }
.sidebar .nav-item:nth-child(8) { animation-delay: 0.8s; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
        overflow: hidden;
    }
    
    .sidebar .nav-link span {
        display: none;
    }
    
    .sidebar .nav-link i {
        font-size: 1.3rem;
        margin-right: 0;
    }
    
    .sidebar .nav-link {
        justify-content: center;
        padding: 1rem 0;
    }
    
    .sidebar:hover {
        width: 250px;
    }
    
    .sidebar:hover .nav-link span {
        display: inline;
    }
    
    .sidebar:hover .nav-link i {
        margin-right: 0.5rem;
    }
}
</style>
<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'movies.php' ? 'active' : '' ?>" href="movies.php">
                    <i class="bi bi-film me-2"></i>
                    Movies
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'categories.php' ? 'active' : '' ?>" href="categories.php">
                    <i class="bi bi-tags me-2"></i>
                    Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'slideshow.php' ? 'active' : '' ?>" href="slideshow.php">
                    <i class="bi bi-images me-2"></i>
                    Slideshow
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'comments.php' ? 'active' : '' ?>" href="comments.php">
                    <i class="bi bi-chat-left-text me-2"></i>
                    Comments
                </a>
            </li>
            <li class="nav-item mt-3">
                <hr class="dropdown-divider">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.php" target="_blank">
                    <i class="bi bi-eye me-2"></i>
                    View Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggler = document.querySelector('.navbar-toggler');

    toggler.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    });
</script>