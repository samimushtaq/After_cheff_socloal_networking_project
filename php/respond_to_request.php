<?php

include 'session_check.php';
include 'Auth.php';

$auth = new Auth();
$userId = $_SESSION['user_id'];
$requestId = $_POST['request_id'] ?? null;
$status = $_POST['status'] ?? null;

// Ensure all required parameters are provided
if (!$requestId || !$status) {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
    exit();
}

// Verify if the friend request belongs to the user
$stmt = $auth->db->prepare("SELECT receiver_id FROM friend_requests WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $auth->db->error]);
    exit();
}
$stmt->bind_param('i', $requestId);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request || $request['receiver_id'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'You are not authorized to respond to this request.']);
    exit();
}

// Update the friend request status
$stmt = $auth->db->prepare("UPDATE friend_requests SET status = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $auth->db->error]);
    exit();
}
$stmt->bind_param('si', $status, $requestId);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Friend request ' . $status . ' successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update request status.']);
}
$stmt->close();
?>
