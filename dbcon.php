<?php

$connection = mysqli_connect("localhost", "root", "", "blogdb");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {

    $_SESSION['user_id'] = '1';
    $_SESSION['username'] = 'fouratmaro';
}
