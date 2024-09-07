<?php

include 'php/session_check.php';
include 'php/Auth.php';



$auth = new Auth();
$userId = $_GET['user_id'];

// Fetch posts
$stmt = $auth->db->prepare("SELECT p.id, p.user_id, p.content, p.image, p.created_at, u.name 
                            FROM posts p JOIN users u ON p.user_id = u.id 
                            ORDER BY p.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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

// Fetch user data
$stmt = $auth->db->prepare("SELECT name, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>


<?php

// Fetch comments and replies
$stmt = $auth->db->prepare("
    SELECT c.id, c.post_id, c.user_id, c.content, c.parent_id, c.created_at, u.name 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    ORDER BY c.created_at ASC
");
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
   

    <?php include('header.php');?>
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 10px;
        }
        .profile-image {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input[type="file"] {
            display: block;
            width: 100%;
        }
        .input-group button {
            background-color: #ffeb3b;
            color: #000;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }


 
        .create-post {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 800px;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .post {
            border-bottom: 1px solid #333;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .post img, #imagePreview {
            max-width: 100%;
            border-radius: 10px;
        }
        .comment-section {
            margin-top: 10px;
            padding-left: 20px;
        }
        .comment, .reply {
            border-top: 1px solid #444;
            padding: 5px 0;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group input, .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #2c2c2c;
            color: #fff;
        }
        .input-group button {
            background-color: #ffeb3b;
            color: #000;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .error {
            color: red;
        }

        
button {
    background-color: #f1c40f;
    color: #333;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color: #d4ac0d;
}

.error {
    color: red;
    font-size: 0.9em;
}
h2
{
    text-align: center;
}

.box {
    border: 1px solid white;
    width: 40%;
    text-align: center;
    display: inline-block;
    vertical-align: top;
    margin: 10px;
}


.box:first-child {
  float: left;
}

.box:last-child {
  float: right;
}

.comment {
    margin-left: 20px;
}

.reply-form {
    margin-left: 40px;
}

.comment > .comment {
    margin-left: 20px;
}
.box
{
    padding: 15px;
}


    </style>
</head>
<body>
    <div class="container">
    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        <form id="profileForm" enctype="multipart/form-data" style="width:139px;margin:auto">
            <div class="input-group">
            
                <img id="profileImagePreview" class="profile-image" src="php/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image Preview">
            </div>
       
        </form>
        <div id="message"></div>


        <div class="box">
<h2>Posts</h2> 
<p>15</p>

        </div>
        <?php
$userId = $_GET['user_id'];

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

<div class="box">
    <h2>Friends</h2>
    <p><?= count($friends) ?></p>
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
        <?php foreach ($posts as $post): ?>
            <div class="post" id="post-<?= $post['id'] ?>">
                <p><strong><?= htmlspecialchars($post['name']) ?></strong> <?= timeAgo($post['created_at']) ?></p>
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
    </div>
</div>

    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="js/profile.js"></script>
</body>
</html>