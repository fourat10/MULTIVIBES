<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$follower_id = $_SESSION['user_id']; // Utilisateur qui suit
$followed_id = $_POST['followed_id']; // Utilisateur à suivre/désabonner

// Vérifier si l'utilisateur essaie de suivre ou de désabonner
$action = $_POST['action']; // Soit "follow" soit "unfollow"

// Si l'utilisateur essaie de suivre
if ($action == 'follow') {
    $query = "INSERT INTO follows (id_follower, id_followee) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $follower_id, $followed_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "You are now following this user!";
    }
} 

// Si l'utilisateur essaie de se désabonner
if ($action == 'unfollow') {
    $query = "DELETE FROM follows WHERE id_follower = ? AND id_followee = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $follower_id, $followed_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "You have unfollowed this user!";
    }
}

header("Location: view-profile.php?id=" . $followed_id); // Redirige vers le profil
exit;
?>
