<?php
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


$stmt = $auth->db->prepare("
    SELECT p.id, p.user_id, p.content, p.image, p.created_at, u.name 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Posts</title>

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

.reply-form {
    margin-top: 10px;
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



    </style>
</head>

<?php include('header.php');?>

<body>





<div class="container">
    <div class="post-feed">
        <?php if (empty($posts)): ?>
            <p>No posts found. You need to make friends first to see their posts.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post" id="post-<?= $post['id'] ?>">
                    <p><strong><?= htmlspecialchars($post['name']) ?></strong> <?= timeAgo($post['created_at']) ?></p>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <?php if ($post['image']): ?>
                        <img src="php/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                    <?php endif; ?>
               
                 
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/feed.js"></script>
</body>
</html>
