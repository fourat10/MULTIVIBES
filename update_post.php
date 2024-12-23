<?php
session_start();
include('connect.php'); // Ensure database connection is included

// Ensure user is logged in before proceeding
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['message'] = 'You must be logged in to edit a post.';
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_SESSION['user_id']; // Ensure the user is logged in
    $id = mysqli_real_escape_string($db, $_GET['id']); // Sanitize the input

    // Fetch post from the database
    $query = "SELECT * FROM post WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("No post found with the given ID."); // Handle no results case
    }
}

// Handle POST request to update Post data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    // Sanitize user inputs
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $content = mysqli_real_escape_string($db, $_POST['content']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $id = mysqli_real_escape_string($db, $_GET['id']); // Use the ID from the GET parameter

    // Handle file upload if a new file is provided
    $imageDestination = $row['photo']; // Keep the existing image if no new file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '-' . $_FILES['image']['name']; // Generate unique file name
        $imageDestination = 'images/' . $imageName;

        if (!move_uploaded_file($imageTmpPath, $imageDestination)) {
            $_SESSION['message'] = 'File upload failed.';
            header('Location: index.php');
            exit;
        }
    }

    // Update the post in the database
    $query = "UPDATE `post` SET `title` = ?, `category_id` = ?, `content` = ?, `photo` = ? WHERE `id` = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'sissi', $title, $category, $content, $imageDestination, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['insert_msg'] = 'Post updated successfully.';
        header('Location: my_posts.php');
        exit;
    } else {
        $_SESSION['message'] = 'Failed to update the post: ' . mysqli_stmt_error($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiVibes</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>

    <style>
        body {
            height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .container {
            flex-grow: 1;
        }
    </style>
</head>

<?php include("include/navbar.php") ?>

<body>
    <div class="container mt-3 mb-3">
        <!-- Form for Editing Post -->
        <form id="editPostForm" action="update_post.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
            <!-- Title Field -->
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="Enter post title" value="<?= htmlspecialchars($row['title'] ?? '') ?>" required>
            </div>
           <!-- Category Selection -->
           <div class="mb-3">
                <label for="category" class="form-label">Category:</label>
                <select class="form-select" name="category" id="category" required>
                    <option disabled value="">Choose a category</option>
                    <?php
                    // Fetch categories from the database
                    $categoryQuery = "SELECT * FROM category";
                    $categoryResult = mysqli_query($db, $categoryQuery);

                    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                        while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                            $selected = ($row['category_id'] == $categoryRow['id']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($categoryRow['id'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($categoryRow['category'], ENT_QUOTES, 'UTF-8') . '</option>';
                        }
                    } else {
                        echo '<option disabled value="">No categories available</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input class="form-control" type="file" name="image" id="image" accept="image/*">
                <p class="text-muted">Current image: <?= htmlspecialchars(basename($row['photo'] ?? '')) ?></p>
            </div>

            <!-- Content Textarea -->
            <div class="form-floating mb-3">
                <textarea name="content" class="form-control" placeholder="Write your content here" id="content" style="height: 150px" required><?= htmlspecialchars($row['content'] ?? '') ?></textarea>
                <label for="content">Content</label>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="update_post" class="btn btn-primary">Update Post</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="index.js"></script>
    <?php include("include/footer.php") ?>
</body>

</html>