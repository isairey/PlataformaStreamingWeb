<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
    /* Admin Header Styles */
.navbar-dark.sticky-top {
    background-color: #0D0D0D;
    border-bottom: 1px solid rgba(229, 9, 20, 0.3);
    padding: 0.5rem 1rem;
    height: 60px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.navbar-brand {
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #E50914 !important;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
}

.navbar-brand::before {
    content: "";
    display: inline-block;
    width: 24px;
    height: 24px;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23E50914"><path d="M18 3H6a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zm-1.5 13.5h-9v-9h9v9z"/></svg>');
    background-size: contain;
    margin-right: 10px;
}

.navbar-toggler {
    border: none;
    padding: 0.5rem;
    margin-right: 1rem;
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 2px rgba(229, 9, 20, 0.5);
}

.navbar-nav {
    flex-direction: row;
}

.nav-item.text-nowrap {
    display: flex;
    align-items: center;
}

.nav-link {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}

.nav-link i {
    margin-right: 6px;
    font-size: 1rem;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link[href="logout.php"] {
    color: #ff6b6b !important;
}

.nav-link[href="logout.php"]:hover {
    background-color: rgba(229, 9, 20, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1rem;
        padding-left: 0.5rem;
    }
    
    .navbar-brand::before {
        width: 20px;
        height: 20px;
    }
    
    .nav-link {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    
    .nav-link i {
        margin-right: 0;
    }
    
    .nav-link span {
        display: none;
    }
    
    .navbar:hover .nav-link span {
        display: inline;
    }
    /* Overlay for sidebar on mobile */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0,0,0,0.5);
    z-index: 90;
    display: none;
}

.sidebar.open {
    transform: translateX(0);
    z-index: 101;
}

@media (max-width: 768px) {
    .sidebar {
        width: 250px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        position: fixed;
        left: 0;
        top: 0;
    }

    .sidebar .nav-link span {
        display: inline;
    }

    .sidebar .nav-link {
        justify-content: start;
        padding-left: 1.5rem;
    }

    .sidebar-overlay.active {
        display: block;
    }

    .navbar-brand img {
        height: 40px;
    }

    .nav-link span {
        display: none;
    }

    .navbar-nav {
        flex-direction: column;
        padding: 0.5rem;
    }
}

}
</style>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="dashboard.php">
        <img src="../assets/logo/cinemax.png" alt="Cinemax Logo" style="height: 60px; object-fit: contain; background-color: white; border-radius: 12px; padding: 4px;">
    </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="w-100"></div>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="../index.php" target="_blank">View Site</a>
        </div>
    </div>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="logout.php">Sign out</a>
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

</header>