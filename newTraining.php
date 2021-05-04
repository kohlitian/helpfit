<?php
//load startup scripts
include("config.php");
include("control.php");

//check if user is logged in
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}

//if a member came to this page, bring him out to home page
if($user['type'] == "member"){
	$_SESSION['passThruMessage'] = "Sorry! You are not allowed to access to the page!";
	header("Location: Home.php"); exit;
}
else {
	//if form is posted
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){


		$nameError = ""; $feeError = ""; $dateError = ""; $classError = ""; $trainingError = "";

		//validate enteries
		if(empty($_POST['sessionName'])){
			$nameError = "please enter a session name";
		} else {
			$name = $_POST['sessionName'];
		}
		if(!empty($_POST['fee'])){
			if($_POST['fee'] < 0){
				$feeError = "Fee cannot be negative";
			} else {
				if(is_numeric($_POST['fee'])){
					$fee = $_POST['fee'];
				} else {
					$feeError = "Fee must be numeric";
				}
			}
		} else {
			$feeError = "Please enter a fee";
		}

		//validate time and make sure time is valid
		if(empty($_POST['datetimepicker'])){
			$dateError = "Please choose a date and time";
		} else {
			if(time()>strtotime($_POST['datetimepicker']) ){
				$dateError = "Today is ".date("Y-m-d H:i:s", time())." new Session must be set after this time.";
			} else {
				$time = strtotime($_POST['datetimepicker']);
			}
		}


		if($_POST['classtype'] == ""){
			$classError = "Please choose a training type";
		} else {
			$classType = $_POST['classtype'];
		}

		if(isset($_POST['trainingType'])){
			if($_POST['trainingType'] == ""){
				if ($_POST["classtype"] == "Group")
						$trainingError = "Please choose a activity";
			} else {
				$trainingType = $_POST['trainingType'];
			}
			if($_POST["classtype"] == "Personal"){
				$participant = 1;
			} else{
				$participant = $_POST['participant'];
			}
		}


		//if there is no error, insert training session into database
		if($nameError =="" && $feeError =="" && $dateError =="" && $classError =="" && $trainingError ==""){
			$newSession = "INSERT INTO `TrainingSessions` (`sessionID`, `title`, `datetime`, `fee`, `status`, `note`, `trainingType`, `classType`, `maxParticipants`, `trainerID`) VALUES ('', '".addslashes($name)."', '$time', '$fee', 'Available', '', '$classType', '$trainingType', '$participant' , '".$user['trainerID']."') ";
			if(mysqli_query($connect, $newSession)){
				$_SESSION['passThruMessage']="Your new session has been added successfully.";
				header('Location: myTraining.php'); exit;
			} 
		}else{
			$passThruMessage="Please correct mentioned errors";
		}
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>New Training</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.min.css">
	<link rel="stylesheet" type="text/css" href="css/helpfit.css">
	
	<style type="text/css">
		form div span{
			color: #9E9F9E;
		}
	</style>
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
		<h3>Add New Training By <?php echo $user['fullName'] ?></h3>
		<form method="POST" action="newTraining.php">
			<div class="marginTB">
				<span>Training Name</span>
				<?php if(isset($nameError)){echo $nameError;} ?>
				<input class="input-lg form-control" type="text" name="sessionName" placeholder="Please enter title of training session" required value="<?php if (isset($_POST['sessionName'])&&$_POST['sessionName']) echo $_POST['sessionName']; ?>">
			</div>
			
			<div class="row">
				<div class="col-sm-6">
					<span>Fee</span>
					<?php if(isset($feeError)){echo $feeError;} ?>
					<div class="input-group noSpaceTop">
						<span class="input-group-addon" id="basic-addon1"><label for="price">RM</label></span>
						<input class="form-control" type="number" name="fee" required id="price" placeholder="Type amount of fee per person"  value="<?php if (isset($_POST['fee'])&&$_POST['fee']) echo $_POST['fee']; ?>">
						<span class="input-group-addon" id="basic-addon1" style="padding-bottom:0px;"><label for="number"><span>.00</span></label></span>
					</div>
				</div>
				
				<div class="col-sm-6">
					<span>Date and Time</span>
					<?php if(isset($dateError)){echo $dateError;} ?>
					<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
						<input class="form-control" type="text" required name="datetimepicker" placeholder="Choose session time"  value="<?php if (isset($_POST['datetimepicker'])&&$_POST['datetimepicker']) echo $_POST['datetimepicker']; ?>">
						<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
					</div>
				</div>
			</div>
			
			<br>
			<div class="row">
				<div class="col-xs-12">
				<span>Training Type:</span>
				<?php if(isset($classError)){echo $classError;} ?>
					<div class="input-group noSpaceTop" id="classtype">
						<span class="input-group-addon" id="basic-addon2"><label for="trainingmode"><i class="fa fa-bolt"></i></label></span>
						<select class="form-control" name="classtype" id="trainingmode" required onchange="applyTrainingType();">
							<option value="">Choose Training Type</option>
							<option value="Group"  <?php if (isset($_POST['classtype'])&&$_POST['classtype']=='Group') echo 'selected'; ?>>Group Training</option>
							<option value="Personal"   <?php if (isset($_POST['classtype'])&&$_POST['classtype']=='Personal') echo 'selected'; ?>>Personal Training</option>
						</select>
					</div>
				</div>
			</div>
			<div id="grouptraining1"></div>
			<div class="row marginTB" id="grouptraining2" style="margin-bottom:10px; display: none;">
				<div class="col-sm-6">
					<span>Choose activity<br/></span>
					
					<?php if(isset($trainingError)){echo $trainingError."<br/>"; } ?>
					<div style="font-size: 17pt;">
						<input type="radio" name="trainingType" value="Dance" id="dance" <?php if (isset($_POST['trainingType'])&&$_POST['trainingType']=='Dance') echo 'checked'; ?>><label class="label label-success" for="dance">Dancing</label><br/>
						<input type="radio" name="trainingType" value="Sport" id="sport"  <?php if (isset($_POST['trainingType'])&&$_POST['trainingType']=='Sport') echo 'checked'; ?>><label class="label label-primary " for="sport">Sport</label><br/>
						<input type="radio" name="trainingType" value="MMA"  <?php if (isset($_POST['trainingType'])&&$_POST['trainingType']=='MMA') echo 'checked'; ?> id="mma"><label class="label label-default" for="mma">MMA</label><br/>
					</div>
				</div>
				
				<div class="col-sm-6">
					<span>Max Participants:</span><br/>
					<div id="grouptraining">
						<input class="slider" id="slider" type="text" name="participant" data-slider-min="1" data-slider-step="1" data-slider-max ="50" data-slider-value="<?php if (isset($_POST['participant'])&&$_POST['participant']){echo $_POST['participant'];}else{ echo 10;} ?>" required>
					</div>
					
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-primary btn-lg">Add This Session</button>

		</form>
	</div>








	<!-- start of footer code -->
	<?php
	$no_helpfit_js=1;
	include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	<script type="text/javascript" src = "js/moment.js"></script>
	<script type="text/javascript" src = "js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src = "js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src = "js/helpfit.js"></script>
	<script>
		applyTrainingType();
	</script>

</body>
</html>