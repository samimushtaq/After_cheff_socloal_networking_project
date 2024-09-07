<?php
include 'Auth.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$auth = new Auth();
$userId = $_SESSION['user_id'];
$profileImagePath = '';
$errors = [];

// Handle profile image upload
if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profileImage']['tmp_name'];
    $fileName = $_FILES['profileImage']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $uploadFileDir = 'uploads/profile_images/';
    $destPath = $uploadFileDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $profileImagePath = 'uploads/profile_images/' . $newFileName;
    } else {
        $errors['profileImage'] = 'There was an error moving the uploaded file.';
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit();
}

// Fetch current profile image path
$stmt = $auth->db->prepare("SELECT profile_image FROM users WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentProfileImagePath = $row['profile_image'];

// Update profile image path only if new file was uploaded
if (empty($profileImagePath)) {
    $profileImagePath = $currentProfileImagePath;
}

$stmt->close();

$stmt = $auth->db->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $auth->db->error]);
    exit();
}

$stmt->bind_param('si', $profileImagePath, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}

$stmt->close();
$auth->db->close();
?>
