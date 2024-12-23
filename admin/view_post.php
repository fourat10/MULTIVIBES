<?php
session_start();
include("../connect.php");

// Vérifier si un ID de post est fourni
if (!isset($_GET['id'])) {
    echo "Invalid post ID.";
    exit;
}

$post_id = $_GET['id'];

// Récupérer les détails du post
$query = "
    SELECT p.title, p.content, p.photo, p.created_at, c.category AS category, u.username
    FROM post p
    JOIN category c ON p.category_id = c.id
    JOIN user u ON p.user_id = u.id
    WHERE p.id = $post_id
";
$post = $db->query($query)->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit;
}

// Récupérer les commentaires du post
$query_comments = "
    SELECT cm.id, cm.comment, u.username 
    FROM comments cm
    JOIN user u ON cm.user_id = u.id
    WHERE cm.post_id = $post_id
    ORDER BY cm.id DESC
";
$comments = $db->query($query_comments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
    <title>View Post</title>
</head>
<body>
<div class="container mt-5">
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
    <p><strong>Created At:</strong> <?php echo $post['created_at']; ?></p>
    <p><strong>Content:</strong> <?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <?php if (!empty($post['photo'])): ?>
        <img src="../<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Image" class="img-fluid">
    <?php endif; ?>

    <h3 class="mt-4">Comments</h3>
    <?php if ($comments && $comments->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                    <?php echo htmlspecialchars($comment['comment']); ?>
                    <a href="delete_comment.php?id=<?php echo $comment['id']; ?>" 
                       class="btn btn-danger btn-sm float-end"
                       onclick="return confirm('Are you sure you want to delete this comment?');">
                        Delete
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No comments found.</p>
    <?php endif; ?>
</div>
</body>
</html>