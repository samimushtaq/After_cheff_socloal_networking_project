// Profile Image Preview
document.getElementById('profileImage').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profileImagePreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// Handle form submission
$('#profileForm').on('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: 'php/update_profile.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            const res = JSON.parse(response);
            if (res.success) {
                $('#message').text(res.message).css('color', 'green');
                location.reload(); // Reload to update the displayed image
            } else {
                $('#message').text(res.message || res.errors).css('color', 'red');
            }
        },
        error: function() {
            $('#message').text('An error occurred. Please try again.').css('color', 'red');
        }
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
                $('#reply-content-' + parentId).val('');
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
});

