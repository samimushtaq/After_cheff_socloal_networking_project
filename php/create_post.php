<?php
include 'session_check.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    $postType = $_POST['postType'];
    $content = trim($_POST['content']);
    $userId = $_SESSION['user_id'];

    // Validate content
    if (empty($content)) {
        $errors['content'] = 'Content is required.';
    } elseif (strlen($content) > 1000) {
        $errors['content'] = 'Content cannot exceed 1000 characters.';
    }

    $imagePath = null;
    if ($postType === 'image') {
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                $errors['image'] = 'Only JPEG, PNG, and GIF formats are allowed.';
            } else {
                $randomNumber = rand(1000, 9999);
                $imagePath = 'uploads/' . $randomNumber . '_' . basename($image['name']);
                if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                    $errors['image'] = 'Failed to upload image.';
                }
            }
        } else {
            $errors['image'] = 'Image is required for image posts.';
        }
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit();
    }

    include 'Auth.php';
    $auth = new Auth();

    // Debugging: Check database connection
    if ($auth->db->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $auth->db->connect_error]);
        exit();
    }

    $stmt = $auth->db->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $auth->db->error]);
        exit();
    }

    $stmt->bind_param('iss', $userId, $content, $imagePath);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create post.']);
    }
}
?>
