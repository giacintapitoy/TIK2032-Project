document.addEventListener("DOMContentLoaded", function() {
    let links = document.querySelectorAll("nav a.nav-link");
    let homeContent = document.getElementById("home");
    let galleryContent = document.querySelector(".gallery");
    let galleryTitle = document.querySelector(".gallery-title");
    let blogContent = document.querySelector(".blog");
    let contactContent = document.querySelector(".contact");

    links.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            links.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            homeContent.style.display = "none";
            galleryContent.style.display = "none";
            galleryTitle.style.display = "none";
            blogContent.style.display = "none";
            contactContent.style.display = "none";

            if (this.getAttribute("href") === "index.html") {
                homeContent.style.display = "flex";
            } else if (this.getAttribute("href") === "gallery.html") {
                galleryContent.style.display = "flex";
                galleryTitle.style.display = "block";
            } else if (this.getAttribute("href") === "blog.html") {
                blogContent.style.display = "block";
            } else if (this.getAttribute("href") === "contact.html") {
                contactContent.style.display = "block";
            }
        });
    });

    // Load comments on page load
    loadComments();
});

// API endpoint
const API_URL = 'comments_api.php';

// Add comment function with database integration
async function addComment(section) {
    const nameInput = document.getElementById(`${section}-name`);
    const commentInput = document.getElementById(`${section}-comment`);
    
    const name = nameInput.value.trim();
    const comment = commentInput.value.trim();
    
    if (name === '' || comment === '') {
        alert('Please fill in both name and comment fields.');
        return;
    }
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                section: section,
                name: name,
                comment: comment
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear inputs
            nameInput.value = '';
            commentInput.value = '';
            
            // Refresh comments display
            loadComments(section);
            
            alert('Comment added successfully!');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error adding comment:', error);
        alert('Error adding comment. Please try again.');
    }
}

// Load comments from database
async function loadComments(section = null) {
    const sections = section ? [section] : ['video', 'recommendations', 'synopsis'];
    
    for (const sec of sections) {
        try {
            const response = await fetch(`${API_URL}?section=${sec}`);
            const result = await response.json();
            
            if (result.success) {
                displayComments(sec, result.comments);
            } else {
                console.error('Error loading comments for', sec, ':', result.message);
            }
        } catch (error) {
            console.error('Error loading comments for', sec, ':', error);
        }
    }
}

// Display comments
function displayComments(section, comments) {
    const commentsList = document.getElementById(`${section}-comments`);
    commentsList.innerHTML = '';
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<p class="no-comments">No comments yet. Be the first to comment!</p>';
        return;
    }
    
    comments.forEach((comment) => {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'comment-item';
        commentDiv.innerHTML = `
            <div class="comment-header">
                <span class="comment-author">${comment.name}</span>
                <span class="comment-time">${comment.timestamp}</span>
                <button onclick="deleteComment(${comment.id})" class="delete-btn">×</button>
            </div>
            <div class="comment-text">${comment.comment}</div>
        `;
        commentsList.appendChild(commentDiv);
    });
}

// Delete comment from database
async function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        try {
            const response = await fetch(`${API_URL}?id=${commentId}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Refresh all comments
                loadComments();
                alert('Comment deleted successfully!');
            } else {
                alert('Error deleting comment: ' + result.message);
            }
        } catch (error) {
            console.error('Error deleting comment:', error);
            alert('Error deleting comment. Please try again.');
        }
    }
}