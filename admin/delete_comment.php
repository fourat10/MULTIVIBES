<?php
session_start();
include("../connect.php");

// Vérifier si un ID de commentaire est fourni
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Invalid comment ID.";
    $_SESSION['message_type'] = "danger";
    header("Location: all_posts.php");
    exit;
}

$comment_id =$_GET['id'];

// Récupérer l'ID du post associé au commentaire
$query = "SELECT post_id FROM Comment WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$stmt->bind_result($post_id);
$stmt->fetch();
$stmt->close();

if (!$post_id) {
    $_SESSION['message'] = "Comment not found.";
    $_SESSION['message_type'] = "danger";
    header("Location: all_posts.php");
    exit;
}
// Supprimer le commentaire
$query = "DELETE FROM comments WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $comment_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Comment deleted successfully.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Failed to delete the comment.";
    $_SESSION['message_type'] = "danger";
}

$stmt->close();

header("Location: view_post.php?id=$post_id");
exit;
?>