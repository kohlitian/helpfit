<?php
//load default startup data
include("config.php");
include("control.php");

//redirect to login if user is not logged in
if(!isset($_SESSION['id']) || !isset($_GET['sessionID'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}

//if trainer came to this page, bring him to home
if($user['type'] == "trainer"){
	$_SESSION['passThruMessage'] = "This page only for member.";
	header("Location: Home.php");
}
else {
	//get session information from database
	if(isset($_GET['sessionID'])){
		$theSessionID = $_GET['sessionID'];
		$information = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `TrainingSessions` WHERE `sessionID` = '".$_GET['sessionID']."'"));
	}

	//validate rating
	$rateError = ""; $commentError = "";
	if(empty($_POST['rating'])){
		$rateError = "Please select a rating";
	} else {
		$rate = $_POST['rating'];
	}

	//validate member comment for trainer
	if(empty($_POST['comment'])){
		$commentError = "Please write comment";
	} else {
		$comment = $_POST['comment'];
	}

	//if no error, insert comment and rating into database
	if($rateError == "" && $commentError == ""){
		mysqli_query($connect, "INSERT INTO `Review` (`reviewID`, `timeStamp`, `rating`, `comment`, `memberID`, `sessionID`) VALUES ('', '".time()."', '$rate', '$comment', '".$user['memberID']."','".$_GET['sessionID']."');");
		$_SESSION['passThruMessage']="Your review has been added successfully.";
		header("Location: myTraining.php"); exit;
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>View my training notes</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/helpfit.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/star-rating.min.css">
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
	<div>
		<div class="container marginTBL" style="margin-bottom:20px;">
			
				
				<h2><i class="fa fa-calendar verybigtext lefty marginright10" style="color: #05C3F7;"></i> <?php if(isset($information)){echo $information['title'];



				;} ?></h2>You can review your trainer and see his notes

		</div>
		<div class="container marginTBL border tabletraining">
			
			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-2 hidden-xs ">
					<span class="lefty marginright10 ">ID</span>
					<span>Participants</span>
				</div>
				<div class="col-sm-4 hidden-xs ">
					<span>Trainer</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Session</span>
				</div>
				<div class="col-sm-3 hidden-xs">
					<span>Date</span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-2 marginTBL">
					<span class="lefty marginright10 hidden-xs idcol"><?php if(isset($information)){ echo $information['sessionID']; } ?></span><span>
						<?php if(isset($_GET['sessionID'])){ echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from joinedsessions where sessionid='".$_GET['sessionID']."';"))[0];} ?>/<?php if(isset($information)) {echo $information['maxParticipants'];} ?>
					</span>
				</div>
				<div class="col-xs-6 col-sm-4">
					<?php
					if(isset($information)){
						 $trainer = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `Trainers` WHERE `trainerID` = '".$information['trainerID']."'"));
					
					$rating = mysqli_query($connect, "SELECT rating FROM `Review`,`TrainingSessions` where review.sessionID=TrainingSessions.sessionID and TrainingSessions.trainerID='".$information['trainerID']."';");
					$totalR = 0;
					$avgR = 0;
						if(mysqli_num_rows($rating) > 0){
							while($findRate = mysqli_fetch_assoc($rating)){
								$totalR += $findRate['rating'];
							}

							$avgR = round($totalR / mysqli_num_rows($rating));

						}
					}

					?>
					<div class="trainer">
								<i class="glyphicon glyphicon-user lefty hidden-xs"></i>
								<span><?php echo $trainer['fullName']; ?></span></br>
								<span><small><?php echo $trainer['specialty']; ?></small>&nbsp;
								

					
						<?php if ($avgR>0){ ?>
							<div class="ratingstarcontainer" style="padding-left:0px;">
							<?php for ($star=1;$star<=5;$star++){ ?>
							<i class="glyphicon glyphicon-star<?php if ($avgR<$star){ echo '-empty';} ?> ratingstar"></i>
							<?php } ?>
						</div>
						<?php  }else{ ?><small>(No rating yet)</small><?php } ?>

									

								</span>
							</div>

				</div>
				<div class="col-xs-6 col-sm-3 marginTBL">
					<span><?php if(isset($information)){ echo $information['classType'];} ?> Class</span><span class="label label-success" style="margin-left:5px;">RM<?php if(isset($information)) {echo $information['fee'];} ?></span><span class="label label-primary" style="margin-left:5px;"><?php if(isset($information)) {echo $information['status'];} ?></span>
				</div>
				<div class="col-xs-6 col-sm-3 marginTBL">
					<span><?php if(isset($information)) {echo date("d M, H:i", $information['datetime']);} ?><small class="transWord hidden-sm"><?php if(isset($information)) {echo date("Y", $information['datetime']);} ?></small></span>
				</div>
	
			</div>
		</div>
		<div class="container marginTBL">
		<?php if(isset($_GET['type']) && $_GET['type'] == "review"){ ?>
			<form method="POST" action="<?php echo "review.php?sessionID=$theSessionID" ?>">
				<h2>Your Review</h2>
				<input required id="input-id" name="rating" type="text" class="ratinginput" data-size="sm" >
				<textarea name="comment" rows="6" class="form-control" required></textarea>
				<br><button type="submit" class="btn btn-primary btn-lg">Submit</button>
			</form>
		</div>
		<?php } else { 

			$get_review=mysqli_fetch_array(mysqli_query($connect,"select comment from review where `sessionid`='".$_GET['sessionID']."' and memberID='".$user['memberID']."' limit 1;"));
			if ($get_review['comment']!=''||$get_review['rate']>0){
			?> <br><br> <div class="well"> You have reviewed this session <button class="btn btn-default" onclick="alert('<?php echo addslashes(str_replace("\n", ". ",str_replace("\r", " ", $get_review['comment']))); ?>');">Read</button></div> <?php }} ?>
		<div class="container marginTBL" style="margin-bottom:30px;">
			<h2>Trainer Notes</h2>
			<blockquote style="font-size:13px;"><?php if(empty($information['note'])){echo "<div class=\"well\"> There is no note yet </div>"; } else {echo $information['note'];} ?></blockquote>
		</div>
	</div>

<!-- start of footer code -->
	<?php
	$need_rating=1;
	 include("footer.php"); ?>
	<!-- end of footer -->

	



</body>
</html>