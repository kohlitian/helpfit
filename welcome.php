<?php
//load startup files
include("config.php");
include("control.php");

//if user is not logged in, redirect to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}

?>


<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Welcome!</title>
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
	<div class="container">

		<div class="row">
			<div class="col-sm-12">

				<br>
				<div class="righty marginleft10 hidden-xs col-sm-4 gallery">
					<img alt="Welcome!" src="img/welcomebanner.jpg" width="100%" />
				</div>
				<h2><i class="fa fa-user-circle verybigtext lefty marginright10" style="color: #05C3F7;"></i> Welcome <?php echo $_SESSION['name']; ?></h2><h4>Your Login Date: <?php echo date("j M Y, H:i", $_SESSION['loginDate']); ?></h4>
				<br><br>
				<p>Welcome to HELPFit system</p>
				<p>Please use navigation menu above to browse through this fitness system</p>
				<br><br>
				<div class="row">
				<?php 
				//find user upcoming trainings and print number of it
				if($user['type'] == "member"){
					$findUp = mysqli_num_rows(mysqli_query($connect, "select * from JoinedSessions, TrainingSessions where JoinedSessions.sessionID=TrainingSessions.sessionID and JoinedSessions.memberID = '".$user['memberID']."' and (TrainingSessions.status='Available' OR TrainingSessions.status = 'Full')")); 
				}
				else if($user['type'] == "trainer"){
					$findUp = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `trainerID` = '".$user['trainerID']."' AND (`status` = 'Available' OR `status` = 'Full')"));
				}
				?>
					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default" href="myTraining.php">
							<span class="verybigtext"><?php if($findUp > 0){echo $findUp;} else {echo "0";} ?></span>
							<div class="smartlinebreak"></div>
							Your Upcoming Trainings
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>
				<?php 
				//find previous training of the user and print number of it
				if($user['type'] == "member"){
					$findPre = mysqli_num_rows(mysqli_query($connect, "select * from JoinedSessions, TrainingSessions where JoinedSessions.sessionID=TrainingSessions.sessionID and JoinedSessions.memberID = '".$user['memberID']."' and TrainingSessions.status='Passed'")); 
				}
				else if($user['type'] == "trainer"){
					$findPre = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `trainerID` = '".$user['trainerID']."' AND `status` = 'Passed'"));
				}else{
					$findPre=0;
				}
				?>

					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default"   href="myTraining.php">
							<span class="verybigtext"><?php if($findPre > 0) {echo $findPre;} else { echo "0";} ?></span>
							<div class="smartlinebreak"></div>
							Your Previous Trainings
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>
				<?php
				//sum of previous and future sessions for this user
				if($user['type'] == "member"){
					$found = mysqli_num_rows(mysqli_query($connect, "select * from JoinedSessions, TrainingSessions where JoinedSessions.sessionID=TrainingSessions.sessionID and JoinedSessions.memberID = '".$user['memberID']."' and (TrainingSessions.status='Passed' or TrainingSessions.status = 'Available' or TrainingSessions.status = 'Full')"));
				}
				else if($user['type'] == "trainer"){
					$found = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `trainerID` = '".$user['trainerID']."' AND (`status` = 'Passed' OR `status` = 'Available' OR `status` ='Full')"));
				}
				?>

					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default"  href="myTraining.php">
							<span class="verybigtext"><?php if($found > 0) {echo $found;} else {echo "0";}?></span>
							<div class="smartlinebreak"></div>
							Upcoming and Previous
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>

				<?php 
				//find all training sessions in our system where are available
				$findAll = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `status` = 'Available'")); 
				?>

					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default"   href="Training.php?status=available">
							<span class="verybigtext"><?php if($findAll>0) {echo $findAll;} else {echo "0";}?></span>
							<div class="smartlinebreak"></div>
							Training Sessions Available
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>

				<?php
				//find all available personal trainings in our system
				$foundPersonal = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `status` = 'Available' AND `trainingType` = 'Personal'")); 
				?>
					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default"   href="Training.php?status=available">
							<span class="verybigtext"><?php if($foundPersonal > 0) {echo $foundPersonal;} else {echo "0";}?></span>
							<div class="smartlinebreak"></div>
							Perosnal Sessions Available
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>
				<?php
				//find all availale group sessions in our system
				$foundGroup = mysqli_num_rows(mysqli_query($connect, "SELECT `sessionID` FROM `TrainingSessions` WHERE `status` = 'Available' AND `trainingType` = 'Group'")); 
				?>

					<div class="col-xs-6 col-md-4">
						<a class="col-xs-12 btn btn-default"   href="Training.php?status=available">
							<span class="verybigtext"><?php if($foundGroup > 0) {echo $foundGroup;} else {echo "0";}?></span>
							<div class="smartlinebreak"></div>
							Group Sessions Available
							<br><br>
						</a>
						<div class="clear"></div><br>
					</div>

				</div>
			</div>
		</div>
	</div>
<br><br>
<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>