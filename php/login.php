<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new Auth();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $auth->login($username, $password);
    
    if ($result['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }
}
?>
