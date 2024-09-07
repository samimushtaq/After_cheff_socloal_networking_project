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
$content = $_POST['content'];
$parentId = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;

$stmt = $auth->db->prepare("INSERT INTO comments (post_id, user_id, content, parent_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param('iisi', $postId, $userId, $content, $parentId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comment added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
}
$stmt->close();
?>
