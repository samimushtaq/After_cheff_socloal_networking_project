<?php
include 'php/session_check.php';
include 'php/Auth.php';




function timeAgo($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}



$auth = new Auth();
$userId = $_SESSION['user_id'];

// Fetch posts from friends
$stmt = $auth->db->prepare("
    SELECT p.id, p.user_id, p.content, p.image, p.created_at, u.name 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    JOIN friend_requests fr ON 
        ((fr.sender_id = ? AND fr.receiver_id = p.user_id) 
        OR (fr.receiver_id = ? AND fr.sender_id = p.user_id))
    WHERE p.user_id != ? AND fr.status = 'accepted'
    ORDER BY p.created_at DESC
");
$stmt->bind_param('iii', $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$auth = new Auth();
$userId = $_SESSION['user_id'];

// Fetch random suggested friends
$stmt = $auth->db->prepare("
    SELECT id, name, profile_image 
    FROM users 
    WHERE id != ? 
    AND id NOT IN (
        SELECT receiver_id 
        FROM friend_requests 
        WHERE sender_id = ?
    )
    ORDER BY RAND() 
    LIMIT 10
");
$stmt->bind_param('ii', $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$suggestedFriends = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>

    <style>
 body {
    font-family: Arial, sans-serif;
    background-color: #333;
    color: #fff;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 800px;
    margin: 20px auto;
}

.create-post, .suggested-friends, .post-feed {
    background-color: #1e1e1e;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
    margin-bottom: 20px;
}

.create-post h2, .suggested-friends h2 {
    margin-top: 0;
    font-size: 24px;
}

button {
    background-color: #f1c40f;
    color: #333;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #d4ac0d;

}

textarea {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    font-size: 14px;
    margin-bottom: 10px;
}

textarea:focus {
    outline: none;
    border-radius: 5px;
background-color: #2c2c2c;
color: #fff;
}

input[type="file"] {
    display: block;
    background-color: #ffeb3b;
    margin-top: 10px;
}

.error {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

#imagePreviewContainer {
    margin-top: 10px;
}

.suggested-friends {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 10px;
}

.suggested-friends .suggested-friend {
    display: inline-block;
    background-color: #1e1e1e;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    margin-right: 10px;
    padding: 10px;
    text-align: center;
    width: 150px;
}

.suggested-friends .suggested-friend img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.suggested-friends .suggested-friend p {
    margin: 10px 0;
    font-size: 16px;
    font-weight: bold;
}

.post-feed .post {
    background-color: #1e1e1e;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    margin-bottom: 20px;
    padding: 20px;
}

.post-feed .post img {
    max-width: 100%;
    border-radius: 8px;
    margin-top: 10px;
}

.comment-section {
    margin-top: 10px;
}

.comment {
    border-top: 1px solid #ddd;
    padding-top: 10px;
    margin-top: 10px;
}

.comment p {
    margin: 0;
}



.input-group {
    margin-top: 10px;
}
.error {
    color: red;
    font-size: 0.9em;
}


@media (max-width: 768px) {
    .container {
        width: 95%;
    }
}

.reply-form {
  margin-top: 10px;
}

.reply-form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

.reply-form button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
}

.reply-form button:hover {
  background-color: #45a049;
}

    </style>
</head>

<?php include('header.php');?>

<body>
<div class="container">
    <div class="create-post">
        <h2>Create Post</h2>
        <div>
            <button id="textPostButton">Text Post</button>
            <button id="imagePostButton">Image Post</button>
        </div>
        <form id="postForm" enctype="multipart/form-data">
            <input type="hidden" id="postType" name="postType" value="text">
            <div id="commonFields">
                <textarea id="postContent" name="content" placeholder="Write something... Below 1000 words.." maxlength="1000" required></textarea>
                <div id="contentError" class="error"></div>
            </div>
            <div id="imageFields" style="display:none;">
                <input type="file" id="postImage" name="image">
                <div id="imagePreviewContainer">
                    <img id="imagePreview" src="" alt="Image Preview" style="display:none; max-width: 100%; height: auto;">
                </div>
                <div id="imageError" class="error"></div>
            </div>
            <button type="submit">Post</button>
            <div id="postMessage"></div>
        </form>
    </div>
</div>




<div class="container">
        <div class="suggested-friends">
            <h2>Suggested Friends</h2>
            <?php foreach ($suggestedFriends as $friend): ?>
                <div class="suggested-friend" id="friend-<?= $friend['id'] ?>">
                    <img src="php/<?= htmlspecialchars($friend['profile_image']) ?>" alt="Profile Image">
                    <p><a href="view_profile.php?user_id=<?= $friend['id'] ?>" style="text-decoration:none;color:#d4ac0d;"><?= htmlspecialchars($friend['name']) ?></a></p>
                    <button onclick="sendFriendRequest(<?= $friend['id'] ?>)">Add Friend</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>




    <?php

function displayComments($comments, $parent_id = null) {
$html = '';
foreach ($comments as $comment) {
    if ($comment['parent_id'] == $parent_id) {
        $html .= '<div class="comment" id="comment-' . $comment['id'] . '">';
        $html .= '<p><strong>' . htmlspecialchars($comment['name']) . '</strong> ' . timeAgo($comment['created_at']) . '</p>';
        $html .= '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
        $html .= '<button onclick="showReplyForm(' . $comment['id'] . ')">Reply</button>';
        $html .= '<div class="reply-form" id="reply-form-' . $comment['id'] . '" style="display: none;">';
        $html .= '<textarea id="reply-content-' . $comment['id'] . '" rows="2" placeholder="Write a reply..."></textarea>';
        $html .= '<button onclick="addReply(' . $comment['post_id'] . ', ' . $comment['id'] . ')">Submit Reply</button>';
        $html .= '</div>';
        $html .= displayComments($comments, $comment['id']);
        $html .= '</div>';
    }
}
return $html;
}
?>


<div class="container">
    <div class="post-feed">
        <?php if (empty($posts)): ?>
            <p>No posts found. You need to make friends first to see their posts.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post" id="post-<?= $post['id'] ?>">
                    <p><a href="view_profile.php?user_id=<?= $post['id'] ?>" style="color:#f1c40f; font-weight: 900;"><?= htmlspecialchars($post['name']) ?></a> <?= timeAgo($post['created_at']) ?></p>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <?php if ($post['image']): ?>
                        <img src="php/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                    <?php endif; ?>
                    <div class="comment-section">
                        <?= displayComments($comments, null) ?>
                    </div>
                    <div class="input-group">
                        <textarea id="comment-<?= $post['id'] ?>" rows="2" placeholder="Write a comment..."></textarea>
                        <button onclick="addComment(<?= $post['id'] ?>)">Comment</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/feed.js"></script>
</body>
</html>
