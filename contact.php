<?php
//load startup script
include("config.php");
include("control.php");

$is_contact=1;

//if form is posted:
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$name = $_POST['name'];
	$title = $_POST['title'];
	$message = $_POST['message'];

	$nameError = ""; $titleError = ""; $messageError = "";

	//validate form data
	if(empty($name)){
		$nameError = "Please enter your name";
	}

	if(empty($title)){
		$titleError = "Please enter a title";
	} 

	if(empty($message)){
		$messageError = "Please enter a message feedback";
	}

	//if there is no error
	if($nameError == "" && $titleError == "" && $messageError == ""){
		$msg = wordwrap($message, 100);
		
		//send email
		if (mail("ben5green@outlook.com", $title, $msg)){
			$_SESSION['passThruMessage'] = "Message sent. Thank You for your feedback, we will review our problem and improve our system base on your feedback.";
			header("Location: Home.php"); exit;
		}
		else { $_SESSION['passThruMessage'] = "Message not sent. Sorry, our email system are currently down, please wait later and sent again.";}
	}
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head> 
	<title>Contact us</title>
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
		<form class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" method="POST" action="contact.php">
			<div class="formStyle">
				<h3 class="noCenter"><Strong>Contact us</Strong></h3>
				<p class="noCenter">You can use this form to send an email to us</p>
				<span>Your name:</span>
				<div class="noSpaceTop">
					<input class="form-control input-lg" required placeholder="Name" type="text" name="name" value="<?php if(isset($user['fullName'])){echo $user['fullName'];} ?>">
				</div>
				<?php if(isset($nameError)){echo $nameError;} ?>
				<span>Subject:</span>
				<div class="noSpaceTop">
					<input class="form-control input-lg" required placeholder="Please enter your subject" type="text" name="title">
				</div>
				<?php if(isset($titleError)){echo $titleError;} ?>
				<span>Message:</span>
				<div class="noSpaceTop">
					<textarea class="form-control input-lg" required rows="5" placeholder="Please enter your message" name="message"></textarea>
				</div>
				<?php if(isset($messageError)){echo $messageError;} ?>
				<button type="submit" class="btn btn-primary btn-lg formButton">Send</button>
			</div>
		</form>

	</div>
<br><br>
<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>