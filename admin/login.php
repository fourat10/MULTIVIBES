<!DOCTYPE html>
<html lang="en">
<?php
include("../connect.php");
error_reporting(0);
session_start();
if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(!empty($_POST["submit"])) 
    {
		$loginquery ="SELECT * FROM admin WHERE username='$username' && password='$password'";
		$result=mysqli_query($db, $loginquery);
		$row=mysqli_fetch_array($result);
		if(is_array($row))
		{
			$_SESSION["admin_id"] = $row['id'];
			header("refresh:1;url=index.php");
		} 
		else
		{
			echo "<script>alert('Invalid Username or Password!');</script>"; 
		}
	}
}
?>
	<title>Login Admin</title>
	<link rel="stylesheet" href="login.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="container">
		<div class="img">
			<img src="images/logiin.jpg">
		</div>
		<div class="login-content">
            <form class="login-form" action="login.php" method="post">
                    <i class="fa-solid fa-user fa-3x" style="color: #39dbaa;"></i>
                    <h2 class="title">Welcome</h2>
                    <div class="input-div one">
                        <div class="i">
                            <i class="fas fa-user"></i> 
                        </div>
                        <div class="div">
                            <input type="text" name="username" class="input" placeholder="Username">
                        </div>
                    </div>
                    <div class="input-div pass">
                        <div class="i"> 
                            <i class="fas fa-lock"></i> 
                        </div>
                        <div class="div">
                            <input type="password" name="password" class="input" placeholder="Password">
                        </div>
                    </div>
                    <input type="submit" class="btn" name="submit" value="Login"/>
            </form>
        </div>
    </div>
</body>
</html>