<?php
session_start();
include 'Auth.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$auth = new Auth();
$senderId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];

// Check if friend request already exists
$stmt = $auth->db->prepare("SELECT id FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
$stmt->bind_param('ii', $senderId, $receiverId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Friend request already sent.']);
    $stmt->close();
    exit();
}
$stmt->close();

// Insert new friend request
$stmt = $auth->db->prepare("INSERT INTO friend_requests (sender_id, receiver_id) VALUES (?, ?)");
$stmt->bind_param('ii', $senderId, $receiverId);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Friend request sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send friend request.']);
}
$stmt->close();
?>
