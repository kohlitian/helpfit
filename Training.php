<?php
//load startup files
include("config.php");
include("control.php");

$is_training=1;

//if user is not logged in, populate the page with guest data
if(!isset($user['fullName'])){

	$user['memberID']=0;
	$user['fullName']='Guest';
	$user['type']='member';

}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>List of all website trainings</title>
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

	<div>
		<div class="container marginTBL">
				
				<h2><i class="fa fa-calendar verybigtext lefty marginright10" style="color: #05C3F7;"></i>Welcome <?php echo $user['fullName']; ?>!</h2><h4><?php 

				//get user created or joined sessions and print it
				if($user['type']== "member"&&$user['memberID']>0 ){
					echo "You have joined ".mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `JoinedSessions` WHERE `memberID` = '".$user['memberID']."'"))." training(s)";}
					 else if($user['type'] == "trainer") {
					 	echo mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `TrainingSessions` WHERE `trainerID` = '".$user['trainerID']."'"))." training(s) created by you";
					 }else{
					 	echo "Login to system to join to classes";
					 }?></h4>
				<br>
				<p>Here's list of our Training Sessions</p>
				
		
		</div>

		<!-- start responsive division to show list of trainings -->
		<div class="container marginTBL border tabletraining">
			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-2 hidden-xs ">
					<span class="lefty marginright10 ">ID</span>
					<span>Title</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Trainer</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Session</span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span>Date</span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span></span>
				</div>
			</div>
			<?php

			
			
			//Populating default pagination data
			if (isset($_GET['pid'])){
				$pid = addslashes($_GET['pid']);if (round($pid)==0){$pid=1;}
			}else{
				$pid = 1;
			}

			//create sql pagination statement
			$sqlpid=($pid-1)*$limit;

			$addsql='';
			$addpagequery='';

			//if user requested to see list of available sessions, create sql statement and pagination statement for that
			if (isset($_GET['status'])&&$_GET['status']=='available'){
				$addsql="and status='Available'";
				$addpagequery="&status=available";
			}

			//request sessions from database
			$training = mysqli_query($connect, "SELECT * FROM `TrainingSessions`  where `status`!='Passed' ".$addsql."  LIMIT ".$sqlpid.", ".$limit.";");
			$i = 1;
			if(mysqli_num_rows($training) > 0){
				while($row = mysqli_fetch_assoc($training)){
					if($user['type'] == "member"&&$user['memberID']>0){
						$check = mysqli_num_rows(mysqli_query($connect, "SELECT `joinID` FROM `JoinedSessions` WHERE `memberID` = '".$user['memberID']."' AND `sessionID` = '".$row['sessionID']."';"));
					}else{
						$check=0;
					}

					//get trainer of session
					$trainer = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `Trainers` WHERE `trainerID` = '".$row['trainerID']."'"));
					
					//get rating of session
					$rating = mysqli_query($connect, "SELECT rating FROM `Review`,`TrainingSessions` where Review.sessionID=TrainingSessions.sessionID and TrainingSessions.trainerID='".$row['trainerID']."';");
					$totalR = 0;
					$avgR = 0;
					//calculate rating of session
						if(mysqli_num_rows($rating) > 0){
							while($findRate = mysqli_fetch_assoc($rating)){
								$totalR += $findRate['rating'];
							}

							$avgR = round($totalR / mysqli_num_rows($rating));

						}

					{
					echo "<div class=\"row\">
						<div class=\"col-xs-6 col-sm-2 marginTBL\">
							<span class=\"lefty marginright10 hidden-xs idcol\">".$row['sessionID']."</span>
							<span>".$row['title']."</span>
						</div>
						<div class=\"col-xs-6 col-sm-3\">
							
							<div class=\"trainer\">
								<i class=\"glyphicon glyphicon-user lefty hidden-xs \"></i>
								<span>".$trainer['fullName']."</span></br>
								<span><small>".$trainer['specialty']."</small>&nbsp;
									
									";

					
						if ($avgR>0){ ?>
							<div class="ratingstarcontainer" style="padding-left:0px;">
							<?php for ($star=1;$star<=5;$star++){ ?>
							<i class="glyphicon glyphicon-star<?php if ($avgR<$star){ echo '-empty';} ?> ratingstar"></i>
							<?php } ?>
						</div>
						<?php  }else{ ?><small>(No rating yet)</small><?php }

									echo "

								</span>
							</div>
							
						</div>
						<div class=\"col-xs-6 col-sm-3 marginTBL\">"; ?>
						<?php if ($row['trainingType']=='Group') { ?><span class="label label-primary"><?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from joinedsessions where sessionid='".$row['sessionID']."';"))[0]; ?>/<?php echo $row['maxParticipants']; ?></span><?php } ?>
						<?php 
						if($user['type'] == "member"){
							echo "<span class=\"label label-success\">".$row['trainingType']." ".$row['classType']."</span>";?><?php if($check >0){ if($row['status'] == 'Cancelled'){ echo "<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['status']."</span>"; } else if($row['status'] == 'Passed') { echo "<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['status']."</span>"; } else if($row['status'] == 'Full'){echo "<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['status']."</span>";} else { echo "<span class=\"label label-warning\" style=\"margin-left:5px;\">Joined</span>";}} else if($row['status'] == 'Available'){ echo"<span class=\"label label-success\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Cancelled') {echo"<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Full'){echo"<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['status']."</span>";} else {echo"<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['status']."</span>";}
						} else if($user['type'] = "trainer") {
							echo "<span class=\"label label-success\">".$row['trainingType']." ".$row['classType']."</span>";?><?php if($row['status'] == 'Available'){ echo"<span class=\"label label-success\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Cancelled') {echo"<span class=\"label label-default\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Full'){echo"<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['status']."</span>";} else {echo"<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['status']."</span>";}
						}
						echo "</div>
						<div class=\"col-xs-6 col-sm-2 marginTBL\">
							<span>".date("d M, H:i", $row['datetime'])."<small class=\"transWord hidden-sm\">".date("Y", $row['datetime'])."</small></span>
						</div>
						<div class=\"col-xs-12 col-sm-2 marginTBL\">";?>
						<?php
						if($user['type'] == "member"){
							if($check >0){ echo "<button class=\"btn btn-warning btn-sm disabled\">Joined</button>";
							} else if($row['status'] == 'Available'){
								echo "<a class=\"btn btn-success btn-sm fullwidth\" href=\"JoinTraining.php?sessionID=".$row['sessionID']."\" onclick=\"return ";
								if ($user['memberID']==0){
									echo "confirm('Please login to system to join events');";
								}
								echo "confirm('Are you sure you want to join this ".$row['title']." by ".$trainer['fullName']." for RM".$row['fee']."?')\"; >";
								if ($user['memberID']==0){
									echo "Costs ";
								}else{
									echo "Join for";
								}
								echo " RM".$row['fee']."</a>";
							} else if($row['status'] == 'Full'){
								echo "<button class=\"btn btn-danger btn-sm disabled\">Full</button>";
							} else if($row['status'] == 'Cancelled') {
								echo "<button class=\"btn btn-basic btn-sm disabled\">Cancelled</button>";
							} else {
								echo "<button class=\"btn btn-info btn-sm disabled\">Passed</button>";
							}
						} else if ($user['type'] == "trainer") {
							if($row['trainerID'] == $user['trainerID']){
								if($row['datetime'] < time()){echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"modifySession.php?sessionID=".$row['sessionID']."&type=view\">View</a>";} else {
									echo "<a class=\"btn btn-success btn-sm fullwidth\" href=\"modifySession.php?sessionID=".$row['sessionID']."&type=edit\">Edit</a>";
								}
							}
						}
						echo"</div>
					</div>";
					}
					
				}
			} else {
				echo "We have currently no training yet";
			}
			?>
		<!-- end responsive division for training list -->
	</div>

	<!-- pagination -->
	<div class="center">
		<ul class="pagination">
			<?php if ($pid>1){ ?>
			<li><a href="?pid=<?php echo $pid-1 ?><?php echo $addpagequery; ?>"><< Previous Page</a></li>
			<?php }else{ ?>
			<li class="disabled"><a href="#" ><< Previous Page</a></li>
			<?php } ?>
				<li class="disabled"><a href="#">Page <?php echo $pid; ?></a></li>
			<?php if (mysqli_num_rows($training)==$limit){ ?>
				<li><a href="?pid=<?php echo $pid+1 ?><?php echo $addpagequery; ?>">Next Page >></a></li>
			<?php }else{ ?>
				<li class="disabled"><a href="#">Next page >></a></li>
			<?php } ?>
		</ul>
	</div>
	<!-- end of pagination -->

	<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	

	

</body>
</html>