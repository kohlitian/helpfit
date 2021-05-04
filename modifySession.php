<?php
//load startup scripts
include("config.php");
include("control.php");

//if user is not logged in, bring him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}


//if user is member, dont allow him access this page
if(isset($user) && $user['type'] == "member"){
	$_SESSION['passThruMessage'] = "Sorry ! You are not allowed to access to the page !";
	header("Location: LogIn.php"); exit;
} else {
	//get session data from database
	if(isset($_GET['sessionID'])){
		$theSessionID = $_GET['sessionID'];
		$theSession = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `TrainingSessions` WHERE `sessionID` = ".$_GET['sessionID'].""));
	}

	//if form is POSTED
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$name = ""; $fee=0; $time=0; $status = ""; $note = ""; $classType = ""; $trainingType = ""; 
		$nameError = ""; $feeError = ""; $dateError = ""; $classError = ""; $trainingError = "";

		$note = $_POST['note'];

		//validate forms entry 

		if(empty($_POST['statusType']) || $_POST['statusType'] == ""){
			$statusError = "Please choose a status";
		} else {
			$status = $_POST['statusType'];
		}

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

		if(empty($_POST['datetimepicker'])){
			$dateError = "Please choose a date and time";
		} else {
			//make sure session date is in future
			if(time()>strtotime($_POST['datetimepicker']) ){
				$dateError = "Today is ".date("Y-m-d H:i:s", time())." new Session must be set after this time.".date("Y-m-d H:i:s");
			} else {
				$time = strtotime($_POST['datetimepicker']);
			}
		}

		if($_POST['classtype'] == ""){
			$classError = "Please choose a training type";
		} else {
			$classType = $_POST['classtype'];
		}

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

		//if there is no validation error, update session in database
		if($nameError =="" && $feeError =="" && $dateError =="" && $classError =="" && $trainingError ==""){
			$newSession = "UPDATE `TrainingSessions` SET `note` = '".addslashes($note)."', `fee` = '$fee', `datetime` = '$time', `trainingType` = '$classType', `classType` = '$trainingType', `status` = '$status', `maxParticipants` = '$participant' WHERE `sessionID` = '".$_GET['sessionID']."';";


			if(mysqli_query($connect, $newSession)){
				$_SESSION['passThruMessage']="Your session has been modified successfully.";
				header("Location: myTraining.php"); die();
			} 
		}else{
			$passThruMessage="Please correct mentioned mistakes.";
			$_GET['type']='edit';
		}
	}
}
?><!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Modify Session</title>
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
		<h1><?php if(isset($theSession)){echo $theSession['title'];} ?></h1>
		<?php
		if(isset($theSession)){
			if (!isset($_GET['type'])||(isset($_GET['type'])&&$_GET['type']=='view')){ 
			$find = mysqli_query($connect, "SELECT * FROM `Member`, `Review` WHERE `Review`.`memberID` = `Member`.`memberID` AND `Review`.`sessionID` = '".$theSession['sessionID']."'");
			if(mysqli_num_rows($find) > 0){
				while($result = mysqli_fetch_assoc($find)){
					echo '<div class="row">
				<div class="col-xs-3 col-sm-2">
					<div class="row col-xs-12">
						<h2><i class="fa fa-user-circle verybigtext lefty marginright10" style="color: #05C3F7;"></i></h2>
					</div>
					<div class="col-xs-12" style="padding-left:0px;">
						<h6>'.$result['fullName'].'</h6>
					</div>
				</div>
				<div class="row col-xs-9">'; ?>


					<?php for ($star=1;$star<=5;$star++){ ?>
							<i class="glyphicon glyphicon-star<?php if ($result['rating']<$star){ echo '-empty';} ?> ratingstar"></i>
					<?php } ?>

				<?php
				echo '</div>
				<div class="row col-xs-9 col-sm-10">
					<blockquote style="font-size: 13px;">'.$result['comment'].'</blockquote>
				</div>
			</div>';
				}
			} else {
				echo "<br><div class='well'>There is no review yet</div><br>";
			}
		}
		} else {
			echo "<div class=\"well\"> Please Select a session to modify, instead of using url to open this page, Thank You. </div>"; die();
		}
		?>
		
			<h3 class="marginTB" style="font-weight:bold; <?php if(isset($_GET['type']) && $_GET['type'] == "edit"){}else{ ?>display: none;<?php } ?>">Modify Session #<?php echo $_GET['sessionID']; ?></h3>





			<form method="POST" action="<?php echo "modifySession.php?sessionID=$theSessionID"; ?>"

			<?php if(isset($_GET['type']) && $_GET['type'] == "edit"){}else{ 
				
				if  ($theSession['trainingType']=='Group'){
				?> style="display: none;" <?php
			}
				 } ?>
				>


<div class="container marginTBL border tabletraining">
			
			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-2 hidden-xs ">
					<span class="lefty marginright10 ">ID</span>
					<span>Participants</span>
				</div>
				<div class="col-sm-4 hidden-xs ">
					<span>Type</span>
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
					<span class="lefty marginright10 hidden-xs idcol"><?php if(isset($theSession)){ echo $theSession['sessionID']; } ?></span><span>
						<?php if(isset($_GET['sessionID'])){ echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from joinedsessions where sessionid='".$_GET['sessionID']."';"))[0];} ?>/<?php if(isset($theSession)) {echo $theSession['maxParticipants'];} ?>
					</span>
				</div>
				<div class="col-xs-6 col-sm-4">
					<span><?php if(isset($theSession)){ echo $theSession['trainingType'];} ?> Class</span>
				</div>
				<div class="col-xs-6 col-sm-3 marginTBL">
					<span><?php if(isset($theSession)){ echo $theSession['classType'];} ?> Class</span><span class="label label-success" style="margin-left:5px;">RM<?php if(isset($theSession)) {echo $theSession['fee'];} ?></span><span class="label label-primary" style="margin-left:5px;"><?php if(isset($theSession)) {echo $theSession['status'];} ?></span>
				</div>
				<div class="col-xs-6 col-sm-3 marginTBL">
					<span><?php if(isset($theSession)) {echo date("d M, H:i", $theSession['datetime']);} ?><small class="transWord hidden-sm"><?php if(isset($theSession)) {echo date("Y", $theSession['datetime']);} ?></small></span>
				</div>
	
			</div>
		</div>
<br><br>



			<div id="grouptraining1" style="display: none;">
				<span>Trainer Notes</span>
				<textarea name="note" rows="5" class="form-control"><?php echo $theSession['note']; ?></textarea>

					<?php if(isset($nameError)){echo $nameError;} ?>
					<input type="hidden" name="sessionName"  value="<?php echo $theSession['title']; ?>">
				
			</div>
				<div class="row" <?php if(isset($_GET['type']) && $_GET['type'] == "edit"){}else{ ?>style="display: none;"<?php } ?>>
					<div class="col-sm-6">
						<span>Fee</span>
						<?php if(isset($feeError)){echo $feeError;} ?>
						<div class="input-group noSpaceTop">
							<span class="input-group-addon" id="basic-addon1"><label for="price">RM</label></span>
							<input class="form-control" type="number" name="fee" required id="price" placeholder="Type amount of fee per person" value="<?php echo $theSession['fee'] ?>">
							<span class="input-group-addon" id="basic-addon1" style="padding-bottom:0px;"><label for="number"><span>.00</span></label></span>
						</div>
					</div>
					<div class="col-sm-6"><span >Date and Time</span>
						<?php if(isset($dateError)){echo $dateError;} ?>
						<div class="input-group date noSpaceTop datetimepicker" id="datetimepicker1">
							<input class="form-control" type="text" required name="datetimepicker" placeholder="Choose session time" value="<?php echo date("m/d/Y H:i A", $theSession['datetime']); ?>">
							<span class="input-group-addon"><label><i class="glyphicon glyphicon-calendar"></i></label></span>
						</div>
					</div>
				</div>
				<br>

				<div class="row" <?php if(isset($_GET['type']) && $_GET['type'] == "edit"){}else{ ?>style="display: none;"<?php } ?>>
					<div class="col-sm-6">
						<span>Training Type:</span>
						<?php if(isset($classError)){echo $classError;} ?>
						<div class="input-group noSpaceTop" id="classtype">
							<span class="input-group-addon" id="basic-addon2"><label for="trainingmode"><i class="fa fa-bolt"></i></label></span>
							<select class="form-control" name="classtype" id="trainingmode" required onchange="applyTrainingType();">
								<option value="">Choose Training Type</option>
								<option <?php if($theSession['trainingType'] == "Group"||(isset($_POST['classtype'])&&$_POST['classtype'] == "Group")){echo "selected";} ?> value="Group">Group Training</option>
								<option <?php if($theSession['trainingType'] == "Personal"&&((isset($_POST['classtype'])&&$_POST['classtype'] != "Group")||!isset($_POST['classtype']))){echo "selected";} ?> value="Personal">Personal Training</option>

							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<span>Status</span>
						<?php if(isset($statusError)){echo $statusError."<br/>"; } ?>
						<div class="input-group noSpaceTop col-xs-12" id="statusType">
							<select class="form-control" name="statusType" required>
								<option value="">Choose Status</option>
								<option <?php if($theSession['status'] == "Available"){echo "selected";} ?> value="available">Available</option>
								<option <?php if($theSession['status'] == "Full"){echo "selected";} ?> value="full">Full</option>
								<option <?php if($theSession['status'] == "Cancelled"){echo "selected";} ?> value="cancelled">Cancelled</option>
							</select>
						</div>
					</div>
				</div>
				<div <?php if(isset($_GET['type']) && $_GET['type'] == "edit"){}else{ ?>style="display: none;"<?php } ?>>
				<div class="row marginTB" id="grouptraining2" style="margin-bottom:10px; display: none;">
					<div class="col-sm-6">
						<span>Choose activity<br/></span>
						<?php if(isset($trainingError)){echo $trainingError."<br/>"; } ?>
						<div style="font-size: 17pt;">
							<input type="radio" name="trainingType" value="Dance" id="dance"  <?php if($theSession['classType'] == "Dance"){echo "checked";} ?> ><label class="label label-success" for="dance">Dancing</label><br/>
							<input type="radio" name="trainingType" value="Sport" id="sport" <?php if($theSession['classType'] == "Sport"){echo "checked";} ?> ><label class="label label-primary" for="sport">Sport</label><br/>
							<input type="radio" name="trainingType" value="MMA" id="mma"  <?php if($theSession['classType'] == "MMA"){echo "checked";}?>><label class="label label-default" for="mma">MMA</label><br/>
						</div>
					</div>
					<div class="col-sm-6">
						<span>Max Participants:</span><br/>
						<div id="grouptraining">
							<input class="slider" id="slider" type="text" name="participant" data-slider-min="1" data-slider-step="1" data-slider-max ="50" data-slider-value=<?php echo $theSession['maxParticipants'] ?> required>
						</div>
					</div>

				</div>
				<br>
			    </div>
				
				<button type="submit" class="btn btn-primary btn-lg">Update</button>
			<br><br>

		</form>
	</div>


	<!-- start of footer code -->
	<?php include("footer.php"); ?>
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