<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new Auth();
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $auth->signup($name, $username, $email, $password);
    echo json_encode($result);
}
?>
