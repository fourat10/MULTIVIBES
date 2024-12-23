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
            /* Full viewport height */
            display: flex;
            /* Enables flexbox for vertical centering if needed */
            flex-direction: column;
            /* Align items vertically */
            margin: 0;
            /* Remove default margin */

        }

        .container {
            flex-grow: 1;
            /* Ensures container fills remaining space */
        }
    </style>
</head>

<?php
session_start();
include('connect.php');

// Initialize the category_table as an empty array
$category_table = [];

$query = "SELECT * FROM category";
$result = mysqli_query($db, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Store the id and category in the category_table array
        $category_table[$row['id']] = $row['category'];
    }
}

// Start the session at the top of the file
?>

<body>
    <?php include("include/navbar.php") ?>
    <br><br>
    <div class="container">

        <button type="button" data-bs-toggle="modal" class="custom-button" data-bs-target="#staticBackdrop"><span class="text">Add Post</span></button>
        <?php
        echo '<br><br>';


        $query = "SELECT post.id, post.user_id, post.title, post.created_at, post.photo, post.category_id, category.category AS category, user.username 
          FROM post
          INNER JOIN category ON post.category_id = category.id
          INNER JOIN user ON post.user_id = user.id
          WHERE user.id = " . $_SESSION['user_id'] . "
          ORDER BY post.created_at DESC";
        $result = mysqli_query($db, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($db));
        } else {
            $counter = 0; // To track the number of cards per row
            echo '<div class="container">'; // Container to hold all rows

            while ($row = mysqli_fetch_assoc($result)) {
                if ($counter % 3 == 0) { // Start a new row for every 3 cards
                    if ($counter > 0) {
                        echo '</div>'; // Close the previous row
                    }
                    echo '<div class="row mb-4">'; // Start a new row
                }

                // Card content for each post
                echo '<div class="col-md-4 d-flex align-items-stretch">';
                echo ' <div class="card" style="width: 100%;">';
                echo ' <img src="' . htmlspecialchars($row['photo']) . '" class="card-img-top" alt="Post Image">';
                echo ' <div class="card-body">';
                echo ' <h4 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                echo '<p class="card-text">Posted by : <a href="view-profile.php?id=' . htmlspecialchars($row['user_id']) . '" class="text-decoration-none"><strong>' . htmlspecialchars($row['username']) . '</strong></a></p>';

                echo ' <p class="card-text">Date : ' . htmlspecialchars($row['created_at']) . '</p>';
                echo ' <p> Category : <a href="posts.php?id=' . htmlspecialchars($row['category_id']) . '" class="btn btn-primary btn-sm">' . htmlspecialchars($row['category']) . '</a> </p>';


                echo '<a href="view-post.php?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-success btn-sm" style="margin-right: 20px;">View</a>';
                echo '<a href="update_post.php?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-warning btn-sm">Edit</a>';
                echo '<a href="delete_post.php?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-warning btn-sm">Delete</a>';

                echo ' </div>';
                echo ' </div>';
                echo '</div>';

                $counter++;
            }

            if ($counter > 0) {
                echo '</div>'; // Close the last row
            }
            echo '</div>'; // Close the container
        }
        ?>
    </div>
    <br><br>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Post :</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPostForm" action="add_post.php" method="post" enctype="multipart/form-data">
                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Enter post title" required>
                        </div>
                        <!-- Category Selection -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <select class="form-select" name="category" id="category" aria-label="Default select example" required>
                                <option selected disabled value="">Choose a category</option>
                                <?php
                                // Loop through the $category_table array and generate options
                                foreach ($category_table as $id => $category) {
                                    echo "<option value=\"$id\">$category</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Image:</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                        </div>

                        <!-- Content Textarea -->
                        <div class="form-floating mb-3">
                            <textarea name="content" class="form-control" placeholder="Write your content here" id="content" style="height: 150px" required></textarea>
                            <label for="content">Content</label>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_post" class="btn btn-primary">Add Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="index.js"></script>
    <?php include("include/footer.php") ?>

</body>

</html>