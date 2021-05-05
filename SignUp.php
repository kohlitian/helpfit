
<?php
//load default startup scripts
include("config.php");
include("control.php");

//if user is already registered, don't allow register again
if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
	$_SESSION['passThruMessage'] = "You have to log out first then only can sign up";
	header("Location: welcome.php"); exit;
}
$no_error=1;

	//if form is posted
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		//get data from form POST method
		$comfirmPassword = $_POST["comfirmPassword"];
		$ID = "";
		$signupDate = time();
		$loginDate = time();
		$level = $_POST['memberlevel'];
		$speciality = $_POST['trainerspecialty'];

		//define required variables
		$usernameError=""; $emailError=""; $passwordError=""; $conPassError=""; $nameError=""; $specialityError = ""; $levelError = ""; $selectError="";

		//validate email
		if(empty($_POST['email'])){
			$emailError = "Email cannot be empty";
		}else {
			 if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			 	$emailError = "Invalid email format";
			 } else {
			 	//make sure email is not used before
			 	$find = "SELECT `email` FROM `Member` WHERE `email` = '".addslashes($_POST['email'])."'";
			 	$findMemberMail = mysqli_query($connect, $find);
			 	$find = "SELECT `email` FROM `Trainers` WHERE `email` = '".addslashes($_POST['email'])."'";
			 	$findTrainerMail = mysqli_query($connect, $find);
			 	if(mysqli_num_rows($findMemberMail) >0 || mysqli_num_rows($findTrainerMail) >0){
			 		$emailError = "Someone have use this email already";
			 	} else {
			 		$email = $_POST['email'];
			 	}
			 }
		}

		//validate username
		if(empty($_POST['username'])){
			$usernameError = "Username cannot be empty";
		}else {
			//make sure username is not used before
		 	$find = "SELECT `username` FROM `Member` WHERE `username` = '".addslashes($_POST['username'])."'";
		 	$findMember = mysqli_query($connect, $find);
		 	$find = "SELECT `username` FROM `Trainers` WHERE `username` = '".addslashes($_POST['username'])."'";
		 	$findTrainer = mysqli_query($connect, $find);
		 	if(mysqli_num_rows($findMember) >0 || mysqli_num_rows($findTrainer) >0){
		 		$usernameError = "Someone have use this username already";
		 	} else {
		 		$username = $_POST['username'];
		 	}
		}
		
		//validate password
		if(empty($_POST['password'])){
			$passwordError = "Password cannot be empty";
		} else {
			$password = $_POST["password"];
		}

		//validate password confirmation
		if(! $comfirmPassword == $password){
			$conPassError = "Both password must be same";
		}

		//validate user full name
		if(empty($_POST['fname'])){
			$nameError = "Name is required";
		} 
		else{
			//make sure name is valid
			if(!(preg_match("/^[a-zA-Z -]+$/", $_POST['fname']))){
				$nameError = "Only letters and white space allowed";
			} else {
				$fname = $_POST["fname"];
			}
		}

		//validate user type and level and speciality
		if($level == "" && $_POST['userType'] == "member"){
			$levelError = "Please choose your level";
		}else if ($speciality == "" && $_POST['userType'] == "trainer"){
			$specialityError = "Please choose your speciality";
		}

		//check if any error occured
		if($emailError == "" && $usernameError == "" && $passwordError == "" && $conPassError == "" && $nameError == "" && $levelError == "" && $specialityError == "" && $selectError == ""){
			//insert data into database if no errors
			if($_POST['userType']=='member'){
				$signUp = "INSERT INTO `Member` (`memberID`, `email`, `username`, `password`, `fullName`, `level`, `signupDate`, `loginDate`) VALUES ('$ID', '".addslashes($email)."','".addslashes($username)."', '".addslashes($password)."', '".addslashes($fname)."', '".addslashes($level)."', '$signupDate', '$loginDate')";
			} else {
				$signUp = "INSERT INTO `Trainers` (`trainerID`, `email`, `username`, `password`, `fullName`, `specialty`, `signupDate`, `loginDate`) VALUES ('$ID', '".addslashes($email)."', '".addslashes($username)."', '".addslashes($password)."', '".addslashes($fname)."', '".addslashes($speciality)."', '$signupDate', '$loginDate')";
			}
			if(mysqli_query($connect, $signUp)){
				//notify user about successfull signup
				$_SESSION['passThruMessage']="Your account has been created successfully. You can login now.";
				header('Location: LogIn.php'); exit;
			}
		}else{
			$no_error=0;

		}
	}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Sign Up</title>
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
		<br><br>
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="#" onsubmit="return validateSignupForm();">



			<div class="formStyle">

				<p><Strong>Register to your HELPFit</Strong></p>
				
				<div class="reg-type center">
					<small>Please choose which user type describe your role:</small>
					<div>
							<a href="#" onclick="btnregtrainer();" class="regtypebtn btn btn-lg btn-primary col-xs-6 noround btnregtrainer" id="btnregtrainer">
								
								<i class="fa fa-slideshare verybigtext"  id="bigicon"></i>
								<div class="smartlinebreaker"></div>
								Trainer
							</a>
		
							<a href="#" onclick="btnregmember();" class="regtypebtn btn btn-lg btn-success col-xs-6 noround btnregmember" id="btnregmember">
								<i class="fa fa-user-circle-o verybigtext" id="bigicon1"></i>
								<div class="smartlinebreaker"></div>
								Member
							</a>
					</div>
					<div class="clear"></div>
				</div>

				<div class="reg-form hidden-always" id="reg-form">



					<div class="form-group hidden-always">
						<select class="form-control" name="userType" id="userType">
							<option value="member">Member</option>
							<option value="trainer">Trainer</option>
						</select>
					</div>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="username"><i class="glyphicon glyphicon-user"></i></label></span>
						<input class="form-control" placeholder="Please enter your username" type="text" id="username" name="username" required value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
					</div>
					<?php if(isset($usernameError)){ ?><span class="error"><?php echo $usernameError; ?></span><?php } ?>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="psw"><i class="fa fa-key"></i></label></span>
						<input class="form-control" placeholder="Enter a new Password" type="password" id="psw" name="password" required  value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>">
					</div>
					<?php if(isset($passwordError)){ ?><span class="error"><?php echo $passwordError; ?></span><?php } ?>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3"><label for="psw"><i class="fa fa-key"></i></label></span>
						<input class="form-control" placeholder="Repeat Password" type="password" id="psw2" name="comfirmPassword" required  value="<?php if (isset($_POST['comfirmPassword'])) echo $_POST['comfirmPassword']; ?>">
					</div>
					<?php if(isset($conPassError)){ ?><span class="error"><?php echo $conPassError; ?></span><?php } ?>

					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="email"><i class="glyphicon glyphicon-envelope"></i></label></span>
						<input class="form-control" placeholder="Please enter your email" type="email" id="email" name="email" required value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
					</div>
					<?php if(isset($emailError)){ ?><span class="error"><?php echo $emailError; ?></span><?php } ?>


					<div class="input-group">
						<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-user-circle"></i></label></span>
						<input class="form-control" placeholder="Full Name" type="text" id="fname" name="fname" required  value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">
					</div>
					<?php if(isset($nameError)){ ?><span class="error"><?php echo $nameError; ?></span><?php } ?>

					<div class="input-group" id="memberelement">
						<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-bolt"></i></label></span>
						<select class="form-control" name="memberlevel" id="memberlevel">
							<option value="">Please choose your level</option>
							<option value="Beginner" <?php if (isset($_POST['memberlevel'])&&$_POST['memberlevel']=='Beginner') echo ' selected'; ?>>Beginner</option>
							<option value="Advanced"  <?php if (isset($_POST['memberlevel'])&&$_POST['memberlevel']=='Advanced') echo ' selected'; ?>>Advanced</option>
							<option value="Expert"  <?php if (isset($_POST['memberlevel'])&&$_POST['memberlevel']=='Expert') echo ' selected'; ?>>Expert</option>
						</select>
					</div>
					<?php if(isset($levelError)){ ?><span class="error"><?php echo $levelError; ?></span><?php } ?>

					<div class="input-group" id="trainerelement">
						<span class="input-group-addon" id="basic-addon2"><label for="fname"><i class="fa fa-bolt"></i></label></span>
						<select class="form-control" name="trainerspecialty" id="trainerspecialty">
							<option value="">Please choose your speciality</option>
							<option value="Dance"  <?php if (isset($_POST['trainerspecialty'])&&$_POST['trainerspecialty']=='Dance') echo ' selected'; ?>>Dance</option>
							<option value="MMA" <?php if (isset($_POST['trainerspecialty'])&&$_POST['trainerspecialty']=='MMA') echo ' selected'; ?>>MMA</option>
							<option value="Sport" <?php if (isset($_POST['trainerspecialty'])&&$_POST['trainerspecialty']=='Sport') echo ' selected'; ?>>Sport</option>
						</select>
					</div>
					<?php if(isset($specialityError)){ ?><span class="error"><?php echo $specialityError; ?></span><?php } ?>
					<?php if(isset($selectError)){ ?><span class="error"><?php echo $selectError; ?></span><?php } ?>
					<button type="submit" class="btn btn-success btn-block btn-lg formButton">Register</button>
				</div>
				<hr>
					<div class="center">
					<small>Or <a href="LogIn.php">login to your account</a></small>
					</div>
			</div>
		</form>
	</div><br><br><br>

<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

<?php
if ($no_error==0){

	if($_POST['userType'] == 'member'){
?>
<script type="text/javascript">btnregmember();

	bootbox.alert("Please correct mentioned errors.");

</script>
<?php 
	} else {
?>
<script type="text/javascript">btnregtrainer();

	bootbox.alert("Please correct mentioned errors.");

</script>
<?php }} ?>

</body>
</html>
