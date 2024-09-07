<?php
include 'php/session_check.php';
include 'php/Auth.php';

$auth = new Auth();
$userId = $_SESSION['user_id'];

// Fetch pending friend requests
$stmt = $auth->db->prepare("SELECT fr.id, u.id as user_id, u.name,u.profile_image 
                            FROM friend_requests fr
                            JOIN users u ON fr.sender_id = u.id
                            WHERE fr.receiver_id = ? AND fr.status = 'pending'");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$friendRequests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  
    <title>Friend Requests</title>
       <style>

        body
        {
            background-color: #1e1e1e;
        }
.container {
    background-color: #1a1a1a; /* Dark background */
    color: #f0db4f; /* Yellow text */
    padding: 20px;
    border-radius: 10px;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
}

h2 {
    color: #f0db4f;
    text-align: center;
font-family: sans-serif;
    border-bottom: 2px solid #f0db4f;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #333;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: background-color 0.3s;
}

li:hover {
    background-color: #444;
}

.profile-container {
    display: flex;
    align-items: center;
}

.profile-img {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin-right: 10px;
}

a.zz {
    color: #f0db4f;
    text-decoration: none;
    font-weight: bold;
    font-family: 'Arial', sans-serif;
}

a.zz:hover {
    text-decoration: underline;
}

.button-container {
    display: flex;
    gap: 10px;
}

button {
    background-color: #f0db4f;
    color: #1a1a1a;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-family: 'Arial', sans-serif;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #e5c73f;
}

p {
    text-align: center;
    font-family: 'Arial', sans-serif;
}



@media (max-width: 480px) {
  .container {
    padding: 10px;
  }
  button {
    padding: 5px 10px;
  }
}
.error {
    color: red;
    font-size: 0.9em;
}


.zz{
text-decoration: none;
color:white;
}

    </style>
</head>
<?php include('header.php');?>

<body>
<div class="container">
    <h2>Friend Requests</h2>
    <?php if (count($friendRequests) > 0): ?>
        <ul>
            <?php foreach ($friendRequests as $request): ?>
                <li>
                    <div class="profile-container">
                        <img src="php/<?= htmlspecialchars($request['profile_image']) ?>" alt="Profile Image" class="profile-img">
                        <a href="view_profile.php?user_id=<?= htmlspecialchars($request['id']) ?>" class="zz"><?= htmlspecialchars($request['name']) ?></a>
                    </div>
                    <div class="button-container">
                        <button onclick="respondToRequest(<?= $request['id'] ?>, 'accepted')">Accept</button>
                        <button onclick="respondToRequest(<?= $request['id'] ?>, 'rejected')">Reject</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No friend requests.</p>
    <?php endif; ?>
</div>


    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="js/friend_request.js"></script>
</body>
</html>
