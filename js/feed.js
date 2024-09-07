document.addEventListener('DOMContentLoaded', function () {
    const postForm = document.getElementById('postForm');
    const logoutButton = document.getElementById('logoutButton');
    const textPostButton = document.getElementById('textPostButton');
    const imagePostButton = document.getElementById('imagePostButton');
    const commonFields = document.getElementById('commonFields');
    const imageFields = document.getElementById('imageFields');
    const postType = document.getElementById('postType');
    const postContent = document.getElementById('postContent');
    const postImage = document.getElementById('postImage');
    const imagePreview = document.getElementById('imagePreview');
    const postMessage = document.getElementById('postMessage');

    // Set default post type to text
    postType.value = 'text';
    imageFields.style.display = 'none';

    textPostButton.addEventListener('click', function () {
        postType.value = 'text';
        imageFields.style.display = 'none';
        imagePreview.style.display = 'none';
        postImage.value = '';
    });

    imagePostButton.addEventListener('click', function () {
        postType.value = 'image';
        imageFields.style.display = 'block';
    });

    postImage.addEventListener('change', function () {
        const file = postImage.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    postForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.getElementById('contentError').textContent = '';
        document.getElementById('imageError').textContent = '';

        const content = postContent.value.trim();
        const image = postImage.files[0];
        let valid = true;

        // Validate content
        if (content === '') {
            document.getElementById('contentError').textContent = 'Content is required.';
            valid = false;
        } else if (content.length > 1000) {
            document.getElementById('contentError').textContent = 'Content cannot exceed 1000 characters.';
            valid = false;
        }

        // Validate image if image post
        if (postType.value === 'image' && !image) {
            document.getElementById('imageError').textContent = 'Image is required for image posts.';
            valid = false;
        }

        if (valid) {
            const formData = new FormData(postForm);

            fetch('php/create_post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                postMessage.textContent = data.message;
                if (!data.success) {
                    if (data.errors.content) {
                        document.getElementById('contentError').textContent = data.errors.content;
                    }
                    if (postType.value === 'image' && data.errors.image) {
                        document.getElementById('imageError').textContent = data.errors.image;
                    }
                } else {
                    // Clear form fields on success
                    postContent.value = '';
                    postImage.value = '';
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }
            })
            .catch(error => {
                postMessage.textContent = 'An error occurred. Please try again.';
                console.error('Error:', error);
            });
        }
    });

    logoutButton.addEventListener('click', function () {
        fetch('php/logout.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.html';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});


$(document).ready(function() {
    loadComments();

    function loadComments() {
        $('.post').each(function() {
            const postId = $(this).attr('id').split('-')[1];
            $.get('php/load_comments.php', { post_id: postId }, function(data) {
                $('#post-' + postId + ' .comment-section').html(data);
            });
        });
    }

    window.addComment = function(postId) {
        const commentContent = $('#comment-' + postId).val();
        if (commentContent.trim() === '') {
            alert('Comment cannot be empty');
            return;
        }

        $.post('php/add_comment.php', { post_id: postId, content: commentContent }, function(response) {
            if (response.success) {
                $('#comment-' + postId).val('');
                loadComments();
            } else {
                alert(response.message);
            }
        }, 'json');
    }

    window.showReplyForm = function(commentId) {
        $('#reply-form-' + commentId).toggle();
    }

    window.addReply = function(postId, parentId) {
        const replyContent = $('#reply-content-' + parentId).val();
        if (replyContent.trim() === '') {
            alert('Reply cannot be empty');
            return;
        }
    
        $.post('php/add_comment.php', { post_id: postId, content: replyContent, parent_id: parentId }, function(response) {
            if (response.success) {
                $('#reply-form-' + parentId).hide();  // Hide the reply form after submission
                loadComments();
            } else {
                alert(response.message);
            }
        }, 'json');
    }
    


    window.deletePost = function(postId) {
        if (confirm('Are you sure you want to delete this post?')) {
            $.post('php/delete_post.php', { post_id: postId }, function(response) {
                if (response.success) {
                    $('#post-' + postId).remove();
                } else {
                    alert(response.message);
                }
            }, 'json');
        }
    }
});




$(document).ready(function() {
    window.sendFriendRequest = function(receiverId) {
        $.post('php/send_friend_request.php', { receiver_id: receiverId }, function(response) {
            if (response.success) {
                alert('Friend request sent successfully.');
                $('#friend-' + receiverId).remove();
            } else {
                alert(response.message);
            }
        }, 'json');
    }
});
