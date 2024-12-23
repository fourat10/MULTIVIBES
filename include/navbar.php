<nav class="navbar navbar-expand-lg" style="background-color:#024CAA;">
  <div class="container-fluid">
    <a class="navbar-brand ms-5 text-white fs-3" href="home.php">MultiVibes <i class="fa-brands fa-servicestack"></i></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="nav nav-underline me-5">
        <li class="nav-item me-3">
          <a class="nav-link text-white" aria-current="page" href="home.php">Home</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link text-white" href="category.php">Categories</a>
        </li>
        <?php
            if(empty($_SESSION["user_id"]))
                {
                    echo '<li class="nav-item me-3"><a href="registration.php" class="nav-link text-white">Register</a> </li>';
                    echo '<li class="nav-item me-3"><a href="login.php" class="nav-link text-white">Login</a> </li>';
                }
            else
                {      
                    echo  '<li class="nav-item me-3"><a href="my_posts.php" class="nav-link text-white">MyPosts</a> </li>';
                    echo  '<li class="nav-item me-3"><a href="logout.php" class="nav-link text-white">Logout</a> </li>';
                }
        ?>
      </ul>
    </div>
  </div>
</nav>