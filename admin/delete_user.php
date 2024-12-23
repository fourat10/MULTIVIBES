<?php
session_start();
include("../connect.php");

// Vérifier si un ID de post est fourni
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Invalid user ID.";
    $_SESSION['message_type'] = "danger";
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];





//supprimer le user
$query_post = "DELETE FROM user WHERE id = ?";
$stmt_post = $db->prepare($query_post);
$stmt_post->bind_param("i", $user_id);

if ($stmt_post->execute()) {
    $_SESSION['message'] = "user deleted successfully.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Failed to delete the user.";
    $_SESSION['message_type'] = "danger";
}

$stmt_post->close();

header("Location: users.php");
exit;
