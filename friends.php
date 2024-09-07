<?php

include 'php/session_check.php';
include 'php/Auth.php';

$auth = new Auth();
$userId = $_SESSION['user_id'];

// Fetch friends
$stmt = $auth->db->prepare("
    SELECT u.id, u.name, u.profile_image 
    FROM users u 
    JOIN friend_requests fr ON (u.id = fr.sender_id OR u.id = fr.receiver_id)
    WHERE (fr.sender_id = ? OR fr.receiver_id = ?) AND u.id != ? AND fr.status = 'accepted'");
$stmt->bind_param('iii', $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <style>
/* Common styles */
body {
    font-family: Arial, sans-serif;
    background-color: #333333;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Header styles */
h2 {
    text-align: center;
    color: white;
}

/* Form container styles */
.form-container {
    background-color: #000;
    padding: 20px;
    margin: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    flex: 1;
    color: #fff;
}

form {
    display: flex;
    flex-direction: column;
}

input[type="text"],
input[type="email"],
input[type="password"],
textarea {
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
}

button {
    background-color: #ffcc00;
    color: #000;
    padding: 10px;
    border: none;
    border-radius: 4px;
    font-size: 1em;
    cursor: pointer;
}

button:hover {
    background-color: #e6b800;
}

/* Error message styles */
.error {
    color: red;
    font-size: 0.9em;
}

/* Responsive styles */
@media (min-width: 768px) {
    .container {
        display: flex;
        background-color: #1e1e1e;
        justify-content: space-between;
    }
}

/* Suggested friends styles */
.suggested-friends,
.friends-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.suggested-friend,
.friend {
    background-color: #1e1e1e;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 150px;
    position: relative;
}

.suggested-friend img,
        .friend img {
            max-width: 100%;
            height: 100px; /* Fixed height */
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover; /* Ensures image fits within the circle */
        }


        
.suggested-friend p,
.friend p {
    margin: 10px 0;
}

.suggested-friend button,
.friend button {
    background-color: #ffcc00;
    color: #000;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.suggested-friend button:hover,
.friend button:hover {
    background-color: #1e1e1e;
}

p
{
    padding: 14px 10px;
    background: #d2ad15;

}
.zz{
text-decoration: none;
color:#000
}

    </style>
</head>

<?php include('header.php');?>

<body>
<div class="container">
    <h2>Your Friends</h2>
    <?php if (empty($friends)): ?>
        <p>You have no friends yet. Start adding friends to see them here.</p>
    <?php else: ?>
        <div class="friends-list">
            <?php foreach ($friends as $friend): ?>
                <div class="friend">
                    <img src="php/<?= htmlspecialchars($friend['profile_image']) ?>" alt="Profile Image">
                    <p><a href="view_profile.php?user_id=<?= htmlspecialchars($friend['id']) ?>" class="zz"><?= htmlspecialchars($friend['name']) ?></a></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>