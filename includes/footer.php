<footer class="site-footer">
    <div class="container">
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="categories.php">Movies</a>
            <a href="trending.php">TV Shows</a>
            <a href="#">About Us</a>
            <a href="contact.php">Contact</a>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>
