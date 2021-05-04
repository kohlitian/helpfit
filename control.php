<?php

//if user is not logged in, but there is cookies available, log user in via cookie
if (!isset($_SESSION['id'])&&isset($_COOKIE['id'])){
	$_SESSION['id']=$_COOKIE['id'];
	$_SESSION['loginDate'] = $_COOKIE['loginDate'];
	$_SESSION['username'] = $_COOKIE['username'];
	$_SESSION['name'] = $_COOKIE['name'];
}


//this debug function is being used during our development phase
function debug($manualID=""){
	global $connect,$autoID;
	if ($manualID=="")
	{
	$autoID++;

	echo "Debug #".$autoID.". ";
	}else{
	echo "Debug #".$manualID.". ";

	}
    echo mysqli_error($connect);
}

	//if user is logged in, verify his account data with database
	if(isset($_SESSION['id'])&&$_SESSION['id'] > 0){
		$member = mysqli_query($connect, "SELECT * FROM `Member` WHERE `fullName` = '".$_SESSION['name']."' AND `username` = '".$_SESSION['username']."' AND `memberID` = '".$_SESSION['id']."'");

		$trainer = mysqli_query($connect, "SELECT * FROM `Trainers` WHERE `fullName` = '".$_SESSION['name']."' AND `username` = '".$_SESSION['username']."' AND `trainerID` = '".$_SESSION['id']."'");

		//if user account found in database, get his full data:
		if(mysqli_num_rows($member) == 1){
			$user = mysqli_fetch_assoc($member);
			$user['type'] = 'member';
		} else if(mysqli_num_rows($trainer) == 1){
			$user = mysqli_fetch_assoc($trainer);
			$user['type'] = 'trainer';
		} else {
			header("Location: logOut.php");
			exit;
		}
	}

	//control training sessions and set their status to passed if session time is passed
	$session = mysqli_query($connect, "SELECT * FROM `TrainingSessions`;");
	if(mysqli_num_rows($session) > 0){
		while($row = mysqli_fetch_assoc($session)){
			if($row['datetime'] < time()){
				mysqli_query($connect, "UPDATE `TrainingSessions` SET `status` = 'Passed' WHERE `sessionID` = '".$row['sessionID']."'");
			} else if ($row['maxParticipants'] != mysqli_num_rows(mysqli_query($connect, "SELECT `joinID` FROM `JoinedSessions` WHERE `sessionID` = '".$row['sessionID']."'"))){
				$row['status'] = 'Available';
			}
		}
	}

//if there is a popup message to show to user, make popup variable ready
if (isset($_SESSION['passThruMessage'])&&$_SESSION['passThruMessage']!=''){
	$passThruMessage=$_SESSION['passThruMessage'];
	$_SESSION['passThruMessage']='';
}else{
	$passThruMessage='';
}
?>