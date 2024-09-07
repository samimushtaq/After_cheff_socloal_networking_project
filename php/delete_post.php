<?php
session_start();
include 'Auth.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$auth = new Auth();
$userId = $_SESSION['user_id'];
$postId = $_POST['post_id'];

// Verify if the post belongs to the user
$stmt = $auth->db->prepare("SELECT user_id, image FROM posts WHERE id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if ($post['user_id'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'You are not authorized to delete this post.']);
    exit();
}

// Delete comments associated with the post
$stmt = $auth->db->prepare("DELETE FROM comments WHERE post_id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$stmt->close();

// Delete the post
$stmt = $auth->db->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$stmt->close();

// Delete associated image file
if ($post['image']) {
    unlink($post['image']);
}

echo json_encode(['success' => true, 'message' => 'Post deleted successfully.']);
?>
