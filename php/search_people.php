<?php

include 'session_check.php';
include 'Auth.php';

$auth = new Auth();
$userId = $_SESSION['user_id'];

// Search for users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $likeQuery = '%' . $query . '%';
    $stmt = $auth->db->prepare("SELECT id, username, name, profile_image FROM users WHERE name LIKE ? AND id != ?");
    if (!$stmt) {
        echo json_encode(['error' => 'Query preparation failed']);
        exit();
    }
    $stmt->bind_param('si', $likeQuery, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (count($users) == 0) {
        echo json_encode(['message' => 'No results found']);
        exit();
    }

    // Check if a friend request is already sent
    foreach ($users as &$user) {
        $stmt = $auth->db->prepare("SELECT status FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
        $stmt->bind_param('ii', $userId, $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $request = $result->fetch_assoc();
        $user['request_status'] = $request ? $request['status'] : null;
        $stmt->close();
    }

    echo json_encode($users);
    exit();
}

// Send or cancel friend requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['receiver_id'])) {
    $receiverId = $_POST['receiver_id'];
    $action = $_POST['action'];

    if ($action === 'send') {
        $stmt = $auth->db->prepare("SELECT id FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
        $stmt->bind_param('ii', $userId, $receiverId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Friend request already sent.']);
        } else {
            $stmt->close();
            $stmt = $auth->db->prepare("INSERT INTO friend_requests (sender_id, receiver_id) VALUES (?, ?)");
            $stmt->bind_param('ii', $userId, $receiverId);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Friend request sent.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send friend request.']);
            }
        }
        $stmt->close();
    } elseif ($action === 'cancel') {
        $stmt = $auth->db->prepare("DELETE FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
        $stmt->bind_param('ii', $userId, $receiverId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Friend request cancelled.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel friend request.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
    exit();
}
?>
