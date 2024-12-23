<?php
session_start();
include('connect.php'); // Make sure this includes your connection to the database

if (isset($_POST['add_post'])) {
    // Collect form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id']; // Ensure the user is logged in

    // Check if the user is logged in
    if (empty($user_id)) {
        $_SESSION['message'] = 'You must be logged in to add a post.';
        header('location:home.php');
        exit;
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '-' . $_FILES['image']['name']; // Generate unique file name
        $imageDestination = 'images/' . $imageName;

        // Move the uploaded file to the destination
        if (move_uploaded_file($imageTmpPath, $imageDestination)) {
            // File upload successful
        } else {
            $_SESSION['message'] = 'File upload failed.';
            header('location:home.php');
            exit;
        }
    } else {
        $_SESSION['message'] = 'Please upload an image.';
        header('location:home.php');
        exit;
    }

    // Insert into database
    $query = "INSERT INTO `post` (title, category_id, content, photo, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query); // Prepare the statement
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sisss', $title, $category, $content, $imageDestination, $user_id); // Bind parameters
        $result = mysqli_stmt_execute($stmt); // Execute the query

        if ($result) {
            $_SESSION['insert_msg'] = 'Post added successfully.';
            header('location:home.php');
            exit;
        } else {
            $_SESSION['message'] = 'Failed to add the post: ' . mysqli_stmt_error($stmt);
        }
    } else {
        $_SESSION['message'] = 'Query preparation failed: ' . mysqli_error($db);
    }
}
