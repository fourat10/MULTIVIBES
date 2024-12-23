<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(0); 
session_start(); 
include("connect.php"); 
if(isset($_POST['submit']))  
{
	$username = $_POST['username'];  
	$password = $_POST['password'];
	
	if(!empty($_POST["submit"]))   
     {
        $loginquery ="SELECT * FROM user WHERE username='$username' && password='".md5($password)."'"; 
        $result=mysqli_query($db, $loginquery);
        $row=mysqli_fetch_array($result);
        if(is_array($row)) 
            {
                $_SESSION["user_id"] = $row['id']; 
                header("refresh:1;url=home.php");
            } 
        else
            {
                $message = "Invalid Username or Password!";
            }
	 }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
    <title>Login Page</title>
    <style>
        #bg_img{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1; 
            filter: blur(10px);
        
        }
        .form_module {
            background: #ffffff;
            max-width: 320px;
            width: 100%;
            border-radius: 50px;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.9);
            margin:120px;
        }
        .form{
            padding: 30px;
        }
        .form h5{
            font-family:'Arial Black';

        }
        #buttn {
            color: white;
            font-weight:bold;
            background-color: #608BC1;
            border-radius:50px;
            cursor: pointer;
            margin-top:30px;
            padding: 10px 80px;
        }
        #buttn:hover{
            background-color: #133E87;
        }
    </style>
</head>
<body>
    <?php include("include/navbar.php") ?>
    <img src="images/login_bg.jpg" id="bg_img"></img>
    <center>
        <div class="form_module">
            <div class="form">
                <h5>Login to your Account</h5>
                <span style="color:red;"><?php echo $message; ?></span>
                <form action="login.php" method="post" style="margin-top:50px;">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username">
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <input type="submit" id="buttn" name="submit" value="Login" />
                </form>
                <div style="margin-top:20px; font-size:13px;">Not registered?<a href="registration.php" style="color:blue;"> Create an account</a></div>
                <div style="margin-top:5px; font-size:13px;"><a href="admin/login.php" style="color:green; text-decoration:none;"> Admin Login</a></div>
            </div>
        </div>
    </center>
    <?php include("include/footer.php") ?>
</body>
</html>