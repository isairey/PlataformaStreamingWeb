document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for slideshow
    const slideshowSwiper = new Swiper('.hero-slider .swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });

    // Initialize Swiper for featured movies carousel
    const featuredMoviesSwiper = new Swiper('.featured-movies-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.featured-movies-swiper .swiper-button-next',
            prevEl: '.featured-movies-swiper .swiper-button-prev',
        },
        on: {
            init: function() {
                console.log('Featured movies swiper initialized');
            }
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
        },
    });

    // Initialize Swiper for each category carousel
    const categorySwipers = document.querySelectorAll('.category-movies-swiper');
    categorySwipers.forEach((swiperEl, index) => {
        const nextBtn = swiperEl.querySelector('.swiper-button-next');
        const prevBtn = swiperEl.querySelector('.swiper-button-prev');

        new Swiper(swiperEl, {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
            },
            on: {
                init: function() {
                    console.log(`Category swiper ${index} initialized`);
                }
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                },
            },
        });
    });

    // Trailer button functionality
    const trailerBtn = document.querySelector('.trailer-btn');
    const trailerModal = new bootstrap.Modal(document.getElementById('trailerModal'));
    const trailerIframe = document.getElementById('trailerIframe');

    trailerIframe.addEventListener('error', function() {
        alert('Failed to load trailer. Please try again later.');
        trailerModal.hide();
    });
    
    if (trailerBtn) {
        trailerBtn.addEventListener('click', function() {
    const originalText = this.innerHTML;
    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
    
    const trailerUrl = this.getAttribute('data-trailer');
    if (trailerUrl) {
        trailerIframe.src = trailerUrl;
        trailerModal.show();
    }
    
    // Reset button text after a short delay
    setTimeout(() => {
        this.innerHTML = originalText;
    }, 1000);
});
    }

    // Reset iframe when modal is closed to stop video playback
    document.getElementById('trailerModal').addEventListener('hidden.bs.modal', function () {
        trailerIframe.src = '';
    });

    // Search functionality with AJAX
    const searchInput = document.querySelector('.search-input');
    const searchResults = document.querySelector('.search-results');

    if (searchInput) {
        let searchTimeout = null;

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();

            clearTimeout(searchTimeout);

            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    fetch(`search.php?q=${encodeURIComponent(query)}`)
                        .then(response => response.text())
                        .then(html => {
                            searchResults.innerHTML = html;
                            searchResults.classList.remove('d-none');
                        })
                        .catch(() => {
                            searchResults.innerHTML = '<div class="p-2 text-center">Error loading results</div>';
                            searchResults.classList.remove('d-none');
                        });
                }, 300); // debounce delay
            } else {
                searchResults.innerHTML = '';
                searchResults.classList.add('d-none');
            }
        });

        // Hide search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.classList.add('d-none');
            }
        });
    }

    // Relative time formatting for comments
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval + " year" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval + " month" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " day" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
        return "Just now";
    }

    function updateCommentTimes() {
        const commentDates = document.querySelectorAll('.comment-date[data-timestamp]');
        commentDates.forEach(span => {
            const timestamp = span.getAttribute('data-timestamp');
            const date = new Date(timestamp);
            if (!isNaN(date)) {
                span.textContent = timeAgo(date);
            }
        });
    }

    updateCommentTimes();
    setInterval(updateCommentTimes, 60000); // Update every minute
});
