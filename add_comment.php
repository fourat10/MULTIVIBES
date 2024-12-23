<?php
session_start();
include("connect.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si les paramètres nécessaires sont passés
if (!isset($_POST['post_id']) || !isset($_POST['comment'])) {
    header("Location: home.php");
    exit();
}

$postId = $_POST['post_id'];
$userId = $_SESSION['user_id'];
$comment = $_POST['comment'];

if (empty($_POST['comment'])){
    header("Location:view-post.php?id=" .$postId);
    exit();
}
// Échapper le contenu du commentaire pour éviter les injections XSS
$comment = htmlspecialchars($comment);

// Insérer le commentaire dans la base de données
$insertCommentQuery = "INSERT INTO comments (comment, user_id, post_id) VALUES (?, ?, ?)";
$stmt = $db->prepare($insertCommentQuery);
$stmt->bind_param("sii", $comment, $userId, $postId);
$stmt->execute();

header("Location: view-post.php?id=" . $postId);
exit();
?>