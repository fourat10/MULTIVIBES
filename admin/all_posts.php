<?php
session_start();
include("../connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
    <title>All Posts</title>
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                width: 100% !important;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php
    include('include/sidebar.php');
    ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">

            <!-- Afficher un message de succès ou d'erreur -->
            <?php if (isset($_SESSION['message'])): ?>
                <div id="message" class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <h1 class="mb-4">All Posts</h1>
            <!-- Tableau des posts -->
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Likes</th>
                        <th>Comments</th>
                        <th>Username</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Requête pour récupérer les posts et les informations associées
                    $query = "
            SELECT 
                p.id, p.title, p.created_at, c.category AS category, 
                u.username, 
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS nb_likes, 
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS nb_comments
            FROM post p
            JOIN category c ON p.category_id = c.id
            JOIN user u ON p.user_id = u.id
            ORDER BY p.created_at DESC
        ";
                    $result = $db->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo $row['nb_likes']; ?></td>
                                <td><?php echo $row['nb_comments']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <a href="view_post.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this post?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">No posts found.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



    <script>
        setTimeout(() => {
            const alert = document.getElementById('message');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>
</body>

</html>