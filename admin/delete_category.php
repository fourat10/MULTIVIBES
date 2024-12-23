<?php
session_start();
include("../connect.php"); 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting category.";
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
}
header("Location: categories.php");
exit();
?>