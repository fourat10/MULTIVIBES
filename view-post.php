<?php
session_start();
include("connect.php");

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

$postId = $_GET['id'];


$query = "SELECT post.*, user.username FROM post 
          JOIN user ON post.user_id = user.id 
          WHERE post.id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Post not found.";
    exit;
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        .post-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .post-card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 400px;
            width: 100%;
            object-fit: cover;
        }

        .btn-like {
            font-size: 18px; 
            padding: 10px 20px;
            width: 150px;
            border-radius: 50px;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-like:hover {
            background-color: #0056b3;
        }

        .btn-comment {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-comment:hover {
            background-color: #218838;
        }

        .comment-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .comment-section h4 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .comment-item {
            background-color: #ffffff;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .comment-item strong {
            font-size: 1.1rem;
        }

        .post-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include("include/navbar.php"); ?>

    <div class="container mt-5 mb-5">
        <!-- Affichage du post -->
        <div class="card post-card">
            <img src="<?php echo htmlspecialchars($post['photo']); ?>" class="card-img-top" alt="Post Image">
            <div class="card-body">
                <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <p class="text-muted">By 
                    <strong><a href="view-profile.php?id=<?php echo $post['user_id']; ?>" style="color:inherit; text-decoration:none;">
                    <?php echo htmlspecialchars($post['username']); ?></a>
                    </strong>
                </p>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        </div>

        <!-- Section Like -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
                <?php
                    // Vérifier le nombre de likes
                    $likeQuery = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
                    $stmt = $db->prepare($likeQuery);
                    $stmt->bind_param("i", $postId);
                    $stmt->execute();
                    $likeResult = $stmt->get_result();
                    $likeCount = $likeResult->fetch_assoc()['like_count'];
                 ?>
                <h4><i class="fa-solid fa-thumbs-up fa-lg"></i>  <?php echo $likeCount; ?> Likes</h4>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php
                    // Vérifier si l'utilisateur a déjà aimé ce post
                    $userId = $_SESSION['user_id'];
                    $userLikeQuery = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
                    $stmt = $db->prepare($userLikeQuery);
                    $stmt->bind_param("ii", $postId, $userId);
                    $stmt->execute();
                    $userLikeResult = $stmt->get_result();
                    $hasLiked = $userLikeResult->num_rows > 0;
                ?>
                <form action="like_post.php" method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <button type="submit" class="btn btn-like">
                        <i class="fas <?php echo $hasLiked ? 'fa-thumbs-down' : 'fa-thumbs-up'; ?>"></i>
                        <?php echo $hasLiked ? 'Dislike' : 'Like'; ?>
                    </button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Log in to like this post</a></p>
            <?php endif; ?>
        </div>

        <!-- Section Commentaires -->
        <div class="comment-section">
            <h4>Comments</h4>
            <?php
            $commentQuery = "SELECT comments.comment, user.username 
                             FROM comments 
                             JOIN user ON comments.user_id = user.id 
                             WHERE comments.post_id = ? 
                             ORDER BY comments.id DESC";
            $stmt = $db->prepare($commentQuery);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $commentResult = $stmt->get_result();

            if ($commentResult->num_rows > 0): ?>
                <div class="list-group">
                    <?php while ($comment = $commentResult->fetch_assoc()): ?>
                        <div class="comment-item">
                            <strong style="color:blue;"><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>

        <!-- Formulaire d'ajout de commentaire -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="add_comment.php" method="POST" class="mt-3">
                <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment..."></textarea>
                </div>
                <button type="submit" class="btn btn-comment">Post Comment</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Log in to add a comment</a></p>
        <?php endif; ?>
    </div>
    <?php include("include/footer.php"); ?>
</body>
</html>