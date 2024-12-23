<div class="d-flex flex-column flex-shrink-0 p-3 bg-light sidebar" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="fs-4">MULTIVIBES</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php
        // Get the current file name
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <li class="nav-item">
            <a href="users.php" class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : 'link-dark'; ?>" aria-current="page">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#home"></use>
                </svg>
                Users
            </a>
        </li>
        <li>
            <a href="all_posts.php" class="nav-link <?php echo ($current_page == 'all_posts.php') ? 'active' : 'link-dark'; ?>">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#speedometer2"></use>
                </svg>
                Posts
            </a>
        </li>
        <li>
            <a href="categories.php" class="nav-link <?php echo ($current_page == 'categories.php') ? 'active' : 'link-dark'; ?>">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#table"></use>
                </svg>
                Categories
            </a>
        </li>

    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
            <li><a class="dropdown-item" href="#">Logout</a></li>
        </ul>
    </div>
</div>