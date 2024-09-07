<?php
include 'Auth.php';

// Ensure `post_id` is provided and valid
if (!isset($_GET['post_id']) || empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    echo 'Invalid request';
    exit();
}

$postId = intval($_GET['post_id']);

$auth = new Auth();
$stmt = $auth->db->prepare("
    SELECT c.id, c.user_id, c.content, c.created_at, c.parent_id, u.name 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ? 
    ORDER BY c.created_at ASC
");
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
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

function displayComments($comments, $parent_id = null, $postId) {
    $html = '';
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            $html .= '<div class="comment" id="comment-' . $comment['id'] . '">';
            $html .= '<p><strong>' . htmlspecialchars($comment['name']) . '</strong> ' . timeAgo($comment['created_at']) . '</p>';
            $html .= '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
            $html .= '<button onclick="showReplyForm(' . $comment['id'] . ')">Reply</button>';
            $html .= '<div class="reply-form" id="reply-form-' . $comment['id'] . '" style="display: none;">';
            $html .= '<textarea id="reply-content-' . $comment['id'] . '" rows="2" placeholder="Write a reply..."></textarea>';
            $html .= '<button onclick="addReply(' . $postId . ', ' . $comment['id'] . ')">Submit Reply</button>';
            $html .= '</div>';
            $html .= displayComments($comments, $comment['id'], $postId);
            $html .= '</div>';
        }
    }
    return $html;
}

echo displayComments($comments, null, $postId);
?>
