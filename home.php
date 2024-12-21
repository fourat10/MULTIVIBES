<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiVibes</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
include('dbcon.php');
// Start the session at the top of the file
?>

<body>
    <?php include("navbar.php") ?>
    <div class="container">
        <div>
            <p>
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. At tempora magni ipsum odio placeat repellendus amet quibusdam, velit fugit ea, laborum, quos id optio eligendi.
            </p>
        </div>
        <?php
        echo "Welcome " . $_SESSION['username'];
        ?>
        <br><br><br>
        <?php
        $query = "
    SELECT *    
    FROM post
    INNER JOIN category ON post.category_id = category.id
    INNER JOIN user ON post.user_id = user.id
    INNER JOIN follows ON follows.id_followee = post.user_id
    WHERE user.id != " . $_SESSION['id'] . " 
      AND follows.id_follower = " . $_SESSION['id'] . "
    ORDER BY post.created_at DESC
";


        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($connection));
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
                echo '    <div class="card" style="width: 100%;">';
                echo '        <img src="' . htmlspecialchars($row['photo']) . '" class="card-img-top" alt="Post Image">';
                echo '        <div class="card-body">';
                echo '            <h4 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                echo '<p class="card-text">Posted by : <a href="#" class="text-decoration-none"><strong>' . htmlspecialchars($row['username']) . '</strong></a></p>';

                echo '            <p class="card-text">Date : ' . htmlspecialchars($row['created_at']) . '</p>';
                echo '  <p>  Category : <a href="posts.php?id=' . htmlspecialchars($row['category_id']) . '" class="btn btn-primary btn-sm">' . htmlspecialchars($row['category']) . '</a> </p>';

                echo '            <a href="view.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-warning btn-sm">View</a>';

                echo '        </div>';
                echo '    </div>';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="index.js"></script>
    <?php include("footer.php") ?>

</body>

</html>