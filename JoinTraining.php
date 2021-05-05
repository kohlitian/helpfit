<?php
//load startup files
include("config.php");
include("control.php");

//if user is not logged in, redirect him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php"); die();
} else {
	//get session info
	$ID = "";
	$session = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `TrainingSessions` WHERE `sessionID` = ".$_GET['sessionID'].""));
	//if session is valid and date of session is in future, join the member to that session
	if(isset($session['datetime']) && isset($user)){
		$check = mysqli_query($connect, "SELECT `memberID` FROM `JoinedSessions` WHERE `memberID` = '".$user['memberID']."' AND `sessionID` = '".$session['sessionID']."'");
		if($session['status'] == 'Available' && $session['datetime'] > time() && $user['type'] == "member" && mysqli_num_rows($check) == 0){
				mysqli_query($connect, "INSERT INTO `JoinedSessions` (`memberID`, `sessionID`) VALUES ('".$user['memberID']."', '".$session['sessionID']."')");
				
		}
	}
}

//a quick control to check and set sessions where participants are full
$session = mysqli_query($connect, "SELECT * FROM `TrainingSessions` where `status` != 'Full';");
if(mysqli_num_rows($session) > 0){
	while($row = mysqli_fetch_assoc($session)){
		$numParticipant = mysqli_num_rows(mysqli_query($connect, "SELECT `joinID` FROM `JoinedSessions` WHERE `sessionID` = '".$row['sessionID']."'"));
		if($row['maxParticipants'] == $numParticipant ){
			mysqli_query($connect, "UPDATE `TrainingSessions` SET `status` = 'Full' WHERE `sessionID` = '".$row['sessionID']."'");

		}
	}
}

//show message to user about joining session
$_SESSION['passThruMessage']="You've joined session #".$_GET['sessionID']." successfully.";
header('Location: myTraining.php');
?>