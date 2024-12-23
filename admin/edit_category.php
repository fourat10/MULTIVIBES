<?php
session_start();
include("../connect.php");

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = $_GET['id'];
$query = $db->prepare("SELECT * FROM category WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Category not found.";
    $_SESSION['message_type'] = "danger";
    header("Location: categories.php");
    exit();
}

$category = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $db->prepare("UPDATE category SET category = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Category updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating category.";
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
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Category</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category['category']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="categories.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
