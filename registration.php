<!DOCTYPE html>
<html lang="en">
<?php
session_start(); 
error_reporting(0); 
include("connect.php");
if(isset($_POST['submit'] )) 
{
     if(empty($_POST['firstname']) ||
   	    empty($_POST['lastname'])|| 
		empty($_POST['password'])||
		empty($_POST['cpassword']))
		{
			$message = "All fields are Required!";
		}
	else
	{
	
	$check_username= mysqli_query($db, "SELECT username FROM user where username = '".$_POST['username']."' ");
		

	
	if($_POST['password'] != $_POST['cpassword']){  
       	
          echo "<script>alert('Password not match');</script>";
    }
	elseif(strlen($_POST['password']) < 6)  
	{
      echo "<script>alert('Password Must be >=6');</script>";
	}
	elseif(mysqli_num_rows($check_username) > 0) 
     {
       echo "<script>alert('Username Already exists!');</script>";
     }
	else{
        $query = "INSERT INTO user(first_name,last_name,username,password) VALUES('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['username']."','".md5($_POST['password'])."')";
        mysqli_query($db, $query);
        $success_message = "🎉 Account created successfully! Redirecting to login page ...";
        header("refresh:3;url=login.php");
    }
	}
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6133de38b3.js" crossorigin="anonymous"></script>
    <title>Sign Up Page</title>
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
        .form_module{
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.9);
            padding: 30px;
            margin:120px;
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
        .form-group {
             margin-bottom: 20px;
        }

        .form-group label {
            margin-bottom: 5px;
        }
        .success-message {
            color: #ffffff;
            background-color: #28a745;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 18px;
            font-family: Arial, sans-serif;
            text-align: center;
            animation: fadeIn 1s;
        }
    </style>
</head>
<body>
    <?php include("include/navbar.php") ?>
    <img src="images/login_bg.jpg" id="bg_img"></img>
    <?php
        if (!empty($success_message)) {
            echo "<div class='success-message'>$success_message</div>";
        }
     ?>
    <center>
        <div class="form_module">
            <form action="registration.php" method="post">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail1">User Name</label>
                        <input class="form-control" type="text" name="username" id="example-text-input" style="width:60%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">First Name</label>
                        <input class="form-control" type="text" name="firstname" id="example-text-input">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Last Name</label>
                        <input class="form-control" type="text" name="lastname" id="example-text-input-2">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputPassword1">Confirm password</label>
                        <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" value="Register" name="submit" id="buttn">
                    </div>
                </div>
            </form>
        </div>
    </center>
    <?php include("include/footer.php") ?>
</body>
</html>