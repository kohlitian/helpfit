<?php
//load startup files
include("config.php");
include("control.php");

//if user is not logged in, redirect him to login page
if(!isset($_SESSION['id'])){
	$_SESSION['passThruMessage'] = "This page only allow subscriber, please log in or sign up then only can access this page.";
	header("Location: login.php");
	die();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>My Subscribed Trainings</title>
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
	<div>
		<div class="container marginTBL">
			
				
				<h2><i class="fa fa-calendar verybigtext lefty marginright10" style="color: #05C3F7;"></i> <?php echo $user['fullName']."'s Trainings"; ?></h2>Here are trainings that you've created
				
			
		</div>
		<div class="container marginTBL border tabletraining">
			

<?php

//populate default pagination data
if (isset($_GET['pid'])){
	$pid = addslashes($_GET['pid']);if (round($pid)==0){$pid=1;}
}else{
	$pid = 1;
}

//create sql page id statement
$sqlpid=($pid-1)*$limit;

			//get list of user created or joined sessions from database
			if($user['type'] == "member"){
				$result = mysqli_query($connect, "SELECT * FROM `JoinedSessions`, `TrainingSessions` WHERE `JoinedSessions`.`sessionID` = `TrainingSessions`.`sessionID` AND `memberID` = '".$user['memberID']."' LIMIT ".$sqlpid.", ".$limit.";");
			} else if($user['type'] == "trainer") {
				$result = mysqli_query($connect, "SELECT * FROM `TrainingSessions` WHERE `trainerID` = '".$user['trainerID']."' LIMIT ".$sqlpid.", ".$limit.";");
			}
			$i = 1;
			if(mysqli_num_rows($result) > 0){

				?>			<div class="row hidden-xs " style="padding-top:10px; padding-bottom:10px; border-top: 0px;">
				<div class="col-sm-2 hidden-xs ">
					<span class="lefty marginright10 ">ID</span>
					<span>Title</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Type</span>
				</div>
				<div class="col-sm-3 hidden-xs ">
					<span>Rating</span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span>Date</span>
				</div>
				<div class="col-sm-2 hidden-xs">
					<span>Action</span>
				</div>
			</div><?php
			//get sessions row by row
				while($row = mysqli_fetch_assoc($result)){

					//get ratings for session and trainer from database
					if($user['type'] == "member"){
					$rating = mysqli_query($connect, "SELECT `rating` FROM `Review` WHERE `sessionID` = '".$row['sessionID']."' and memberID='".$user['memberID']."';");

					}else{
					$rating = mysqli_query($connect, "SELECT `rating` FROM `Review` WHERE `sessionID` = '".$row['sessionID']."'");
					}
					$totalR = 0;
					$avgR = 0;
						//calculate the rating to show
						if(mysqli_num_rows($rating) > 0){
							while($findRate = mysqli_fetch_assoc($rating)){
								$totalR += $findRate['rating'];
							}
							$avgR = round($totalR / mysqli_num_rows($rating));
						}

				echo "<div class=\"row\">
					<div class=\"col-xs-6 col-sm-2 marginTBL\">
						<span class=\"lefty marginright10 hidden-xs idcol\">".$row['sessionID']."</span>
						<span>".$row['title']."</span>
					</div>
					<div class=\"col-xs-6 col-sm-3 marginTBL\">
						<div class=\"trainer\">";
							if ($row['trainingType']=='Group') { ?><span class="label label-primary hidden-sm"><?php echo mysqli_fetch_array(mysqli_query($connect,"select count(*) from joinedsessions where sessionid='".$row['sessionID']."';"))[0]; ?>/<?php echo $row['maxParticipants']; ?></span>&nbsp;<?php } 
							echo "<span class=\"label label-success\">".$row['trainingType']." ".$row['classType']."</span>";?>
							
							<?php if($row['status'] == 'Available'){ echo"<span class=\"label label-success\" style=\"margin-left:5px;\">".$row['status']."</span>";} else if($row['status'] == 'Cancelled') {echo"<span class=\"label label-default\">".$row['status']."</span>";} else if($row['status'] == 'Full'){echo"<span class=\"label label-danger\" style=\"margin-left:5px;\">".$row['status']."</span>";} else {echo"<span class=\"label label-info\" style=\"margin-left:5px;\">".$row['status']."</span>";}
					echo "</div>
						
					</div>
					<div class=\"col-xs-6 col-sm-3 marginTBL\">"; ?>
					<?php 
						if ($avgR>0){ ?>
							<div class="ratingstarcontainer" style="padding-left:0px;">
							<?php for ($star=1;$star<=5;$star++){ ?>
							<i class="glyphicon glyphicon-star<?php if ($avgR<$star){ echo '-empty';} ?> ratingstar"></i>
							<?php } ?>
						</div>
						<?php  }else{ ?>No rating yet<?php } ?>
					<?php 
					echo "</div>
					<div class=\"col-xs-6 col-sm-2 marginTBL\">
						<span>".date("d M, H:i", $row['datetime'])."<small class=\"transWord hidden-sm\">".date("Y", $row['datetime'])."</small></span>
					</div>
					<div class=\"col-xs-12 col-sm-2\" style=\"padding-top:1px;\">";?>
					<?php
					if($user['type'] == "member"){
						$review = mysqli_num_rows(mysqli_query($connect, "SELECT `reviewID` FROM `Review` WHERE `sessionID` = '".$row['sessionID']."' AND `memberID` = '".$user['memberID']."'"));
						if($row['datetime'] < time() && $review >=1 ){echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"review.php?sessionID=".$row['sessionID']."&type=view\">View</a>";} else if($row['datetime'] < time() && $review ==0 ){echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"review.php?sessionID=".$row['sessionID']."&type=review\">Reiew</a>";}else if($row['datetime'] > time()){echo "<a class=\"btn  btn-primary btn-sm fullwidth\" href=\"review.php?sessionID=".$row['sessionID']."&type=view\" onclick=\"return true;');\">View</a>"; }
					} else {
						if($row['datetime'] < time()){echo "<a class=\"btn btn-primary btn-sm fullwidth\" href=\"modifySession.php?sessionID=".$row['sessionID']."&type=view\">View</a>";} else {
								echo "<a class=\"btn btn-success btn-sm fullwidth\" href=\"modifySession.php?sessionID=".$row['sessionID']."&type=edit\">Edit</a>";
							}
					}
					echo "</div>
				</div>";
			 }
		}else{
		?>

		<div class="well">You don't have any trainings yet.</div>
		<?php

		}
			?>
		
		</div>

	<!-- pagination -->
	<div class="center">
		<ul class="pagination">
			<?php if ($pid>1){ ?>
			<li><a href="?pid=<?php echo $pid-1 ?>"><< Previous Page</a></li>
			<?php }else{ ?>
			<li class="disabled"><a href="#" ><< Previous Page</a></li>
			<?php } ?>
				<li class="disabled"><a href="#">Page <?php echo $pid; ?></a></li>
			<?php if (mysqli_num_rows($result)==$limit){ ?>
				<li><a href="?pid=<?php echo $pid+1 ?>">Next page >></a></li>
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