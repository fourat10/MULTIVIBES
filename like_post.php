<?php
session_start();
include("connect.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID du post est passé en paramètre
if (!isset($_POST['post_id'])) {
    header("Location: home.php");
    exit();
}

$postId = $_POST['post_id'];
$userId = $_SESSION['user_id'];

// Vérifier si l'utilisateur a déjà aimé ce post
$likeQuery = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
$stmt = $db->prepare($likeQuery);
$stmt->bind_param("ii", $postId, $userId);
$stmt->execute();
$likeResult = $stmt->get_result();

if ($likeResult->num_rows > 0) {
    // Si le like existe déjà, on le retire
    $deleteLikeQuery = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $db->prepare($deleteLikeQuery);
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
} else {
    // Sinon, on ajoute le like
    $insertLikeQuery = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
    $stmt = $db->prepare($insertLikeQuery);
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
}

header("Location: view-post.php?id=" . $postId);
exit();
?>