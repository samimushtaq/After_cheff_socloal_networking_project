<?php
include 'Auth.php';

$auth = new Auth();
$result = $auth->logout();
echo json_encode($result);
?>
