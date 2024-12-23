<?php
session_start();
include("../connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    if (!empty($name)) {
        $stmt = $db->prepare("INSERT INTO category (category) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Category added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding category.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['message_type'] = "warning";
    }
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add Category</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Add Category</h1>
        <form action="add_category.php" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Category Name" name="name">
                <label for="floatingInput">Category Name</label>
            </div>
            <button type="submit" class="btn btn-success">Add</button>
            <a href="categories.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>