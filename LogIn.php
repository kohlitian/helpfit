<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is already logged in, bring him to welcome page
if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
	header("Location: welcome.php"); exit;
}

//if login form is POSTed
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$username = $_POST['username'];
	$password = $_POST['password'];

	$usernameError = ""; $passwordError = ""; $logInError = "";

	//validate username and password
	if(empty($username)){
			$usernameError = "Username cannot be empty";
	}
	
	if(empty($password)){
		$passwordError = "Password cannot be empty";
	}

	//look into database for entered user/pass for member
    $findMember = mysqli_query($connect, "SELECT * FROM `Member` WHERE `username` = '".addslashes($username)."' AND `password` = '".addslashes($password)."';");
	//look into database for entered user/pass for trainer
    $findTrainer = mysqli_query($connect, "SELECT * FROM `Trainers` WHERE `username` = '".addslashes($username)."' AND `password` = '".addslashes($password)."';");

    //if user is found
    if(mysqli_num_rows($findMember) == 1 || mysqli_num_rows($findTrainer) == 1){
    	
    	//update user login time
	    if(mysqli_num_rows($findMember) == 1){
	    	$user = mysqli_fetch_assoc($findMember);
	    	$_SESSION['id'] = $user['memberID'];
	    	mysqli_query($connect, "UPDATE `Member` SET `loginDate` = '".time()."' WHERE `memberID` = '".$_SESSION['id']."'");
	   	}
	    else {
	    	$user = mysqli_fetch_assoc($findTrainer);
	    	$_SESSION['id'] = $user['trainerID'];

	    	mysqli_query($connect, "UPDATE `Trainers` SET `loginDate` = '".time()."' WHERE `trainerID` = '".$_SESSION['id']."'");

	    }

	    //populate session with user data
	    $_SESSION['loginDate'] = $user['loginDate'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['name'] = $user['fullName'];

		//populate cookie with user data
		setcookie('id', $_SESSION['id'], time() + (86400 * 30), "/");
		setcookie('loginDate', $_SESSION['loginDate'], time() + (86400 * 30), "/");
		setcookie('username', $_SESSION['username'], time() + (86400 * 30), "/");
		setcookie('name', $_SESSION['name'], time() + (86400 * 30), "/");
		
		//go to welcome page
		header("Location: welcome.php"); die();
	} else{
    	$passThruMessage = "Username or Password incorrect";
    }

}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Login</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/helpfit.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	
</head>
<body>
	<!-- start of header -->
	<?php 
		if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
			include("headerAfterLog.php");
		} else{
			include("header.php"); 
		}
	?>
	<!-- end of header -->

	<!--content-->
	<div class="container marginTB">
		<br>
		<br>
		<br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="LogIn.php">
			<div class="formStyle">
				<p><Strong>Login to your HELPFit account</Strong></p>
				<div class="input-group">
				  	<span class="input-group-addon" id="basic-addon1"><label for="username"><i class="glyphicon glyphicon-user"></i></label></span>
					<input type="text" required class="form-control" placeholder="Please enter your username" id="username" name="username">
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2"><label for="pwd"><i class="fa fa-key"></i></label></span>
					<input class="form-control" required placeholder="Please enter your password" type="password" id="pwd" name="password">
				</div>
				<button type="submit" class="btn btn-success btn-block btn-lg formButton">Log In</button>
				<?php if(isset($usernameError)){echo $usernameError;}?>
				<?php if(isset($passwordError)){echo $passwordError;}?>
				<hr>
				<div class="center">
				<small>Or <a href="signup.php">sign up for a new account</a></small>
				</div>
			</div>
		</form>
		<div class="clear"></div>
		<br>
		<br>
		<br>
	</div>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>