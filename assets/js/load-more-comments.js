document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more-comments');
    const commentsList = document.getElementById('comments-list');
    if (!loadMoreBtn || !commentsList) return;

    let offset = commentsList.children.length;
    const movieId = new URLSearchParams(window.location.search).get('id');
    const limit = 5;

    loadMoreBtn.addEventListener('click', function () {
        fetch(`comments.php?movie_id=${movieId}&offset=${offset}&limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.classList.add('comment', 'card', 'p-3', 'mb-3', 'bg-dark', 'rounded');
                        commentDiv.innerHTML = `
                            <div class="comment-header d-flex justify-content-between mb-2">
                                <span class="user-name fw-bold">${comment.user_name}</span>
                                <span class="comment-date text-muted" data-timestamp="${comment.created_at}">${new Date(comment.created_at).toLocaleString()}</span>
                            </div>
                            <div class="comment-text">${comment.comment}</div>
                        `;
                        commentsList.appendChild(commentDiv);
                    });
                    offset += data.comments.length;
                    if (data.comments.length < limit) {
                        loadMoreBtn.style.display = 'none';
                    }
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading more comments:', error);
                loadMoreBtn.style.display = 'none';
            });
    });
});
