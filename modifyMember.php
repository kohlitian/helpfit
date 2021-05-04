<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is not logged in, ask him to login
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	exit;
}

//if user is trainer, dont allow him access this page
if(isset($user) && $user['type'] == "trainer"){
	$_SESSION['passThruMessage'] = "Sorry! You are not allowed to access this page!";
	header("Location: Home.php"); exit;
} else{

	//if form is posted:
	if($_SERVER['REQUEST_METHOD'] == 'POST'){


		$usernameError=""; $passwordError = ""; $nameError = ""; $emailError = ""; $levelError = "";
		

		//validate form data

		if(!empty($_POST['memberlevel'])){
			$level = $_POST['memberlevel'];
		} else if($_POST['memberlevel'] == ""){
			$levelError = "(Please select a level)";
		} else {
			$level = $user['level'];
		}

		if(empty($_POST['password'])){
			$password = $user['password'];
		} else {
			$password = $_POST['password'];
			if(empty($_POST['conPass']) || $_POST['conPass'] != $password){
				$passwordError = "(Both password must be same)";
			}
		}

		if(empty($_POST['fname'])){
			$nameError = "(Please enter a name)";
		} else {
			if(!(preg_match("/^[a-zA-Z -]+$/", $_POST['fname']))){
				$nameError = "(Only letters and white space allowed)";
			} else {
				$fname = $_POST['fname'];
			}
		}

		//validate email
		if(empty($_POST['email'])){
			$emailError = "(Please enter a email)";
		} else {
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			 	$emailError = "(Invalid email format)";
			 } else {
			 	//make sure email is not used by others
			 	$find = "SELECT `email` FROM `Member` WHERE `email` = '".addslashes($_POST['email'])."' and `memberID`!='".addslashes($user['memberID'])."';";
			 	$find2 = "SELECT `email` FROM `Trainers` WHERE `email` = '".addslashes($_POST['email'])."';";
			 	$findMemberMail = mysqli_query($connect, $find);
			 	$findMemberMail2 = mysqli_query($connect, $find2);
			 	if(mysqli_num_rows($findMemberMail) >0 || mysqli_num_rows($findMemberMail2) >0 ){
			 		$emailError = "(Someone have use this email already)";
			 	} else {
			 		$email = $_POST['email'];
			 	}
			 }
		}

		//validate username
		if(empty($_POST['username'])){
			$usernameError = "(Please enter a username)";
		} else {

				//make sure username is not used by others
			
			 	$find = "SELECT `username` FROM `Member` WHERE `username` = '".addslashes($_POST['username'])."' and `memberID`!='".addslashes($user['memberID'])."';";
			 	$find2 = "SELECT `username` FROM `Trainers` WHERE `username` = '".addslashes($_POST['username'])."';";
			 	$findMember = mysqli_query($connect, $find);
			 	$findMember2 = mysqli_query($connect, $find2);
			 	if(mysqli_num_rows($findMember) >0 || mysqli_num_rows($findMember2) >0 ){
			 		$usernameError = "(Someone have use this username already)";
			 	} else {
			 		$username = $_POST['username'];
			 	}
			 
		}

		//if there is no validation error, update member info in database
		if($passwordError == "" && $usernameError == "" && $nameError == "" && $emailError == "" && $levelError == ""){
			if(mysqli_query($connect, "UPDATE `Member` SET `password` = '".addslashes($password)."', `username` = '".addslashes($username)."', `email` = '".addslashes($email)."', `fullName` = '".addslashes($fname)."', `level` = '".addslashes($level)."' WHERE `memberID` = '".$user['memberID']."'")){
				$_SESSION['name'] = $fname;
				$_SESSION['username'] = $username;
				$_SESSION['passThruMessage']="Your member info has been updated successfully.";
				header("Location: welcome.php");
			}
			;
		}else{
			$passThruMessage="Please correct mentioned errors.";
		}
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Modify Member</title>
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
		if($_SESSION['id'] > 0){
			include("headerAfterLog.php");
		} else{
			include("header.php"); 
		}
	?>
	<!-- end of header -->

	<!--content-->
	<div class="container marginTB">
		<br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" onsubmit="return validateMemberForm();" method="POST" action="modifyMember.php">
			<div class="formStyle">
				<p><Strong>Modify your member account info</Strong></p>
				<span>New Password:<br>(Leave password empty to keep unchanged)</span>
				<?php if(isset($passwordError)){ echo $passwordError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="psw"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Please enter a new password  (optional)" type="password" name="password" id="psw">
				</div>
				<span>Repeat Password again:</span>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon3"><label for="psw"><i class="fa fa-key"></i></label></span>
					<input class="form-control" placeholder="Repeat Password" type="password" id="psw2" name="conPass">
				</div>
							
				<span>Username:</span>
				<?php if(isset($usernameError)){ echo $usernameError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-user"></i></label></span>
					<input class="form-control" placeholder="Please enter your username" type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
				</div>
								
				<span>Email:</span>
				<?php if(isset($emailError)){ echo $emailError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-envelope"></i></label></span>
					<input class="form-control" placeholder="Please enter your email" type="email" id="email" name="email" value="<?php echo $user['email']; ?>">
				</div>
	
				<span>Name:</span>
				<?php if(isset($nameError)){ echo $nameError;} ?>
				<div class="input-group noSpaceTop">
					<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-user-circle"></i></label></span>
					<input class="form-control" placeholder="Please enter your Full Name" type="text" id="fname"  name="fname" value="<?php echo $user['fullName']; ?>">
				</div>
			
				<span>Level:</span>
				<?php if(isset($levelError)){ echo $levelError;} ?>
				<div class="input-group noSpaceTop" id="memberelement">
					<span class="input-group-addon" id="basic-addon2"><label for="memberlevel"><i class="fa fa-bolt"></i></label></span>
					<select class="form-control" name="memberlevel" id="memberlevel">
						<option value="">Choose your level</option>
						<option <?php if($user['level'] == 'Beginner'){ echo 'selected';} ?> value="Beginner">Beginner</option>
						<option <?php if($user['level'] == 'Advanced'){ echo 'selected';} ?> value="Advanced">Advanced</option>
						<option <?php if($user['level'] == 'Expert'){ echo 'selected';} ?> value="Expert">Expert</option>
					</select>
				</div>
				
				<button type="submit" class="btn btn-success btn-block btn-lg formButton">Update</button>
			</div>
		</form>
	</div>
<br>
<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>