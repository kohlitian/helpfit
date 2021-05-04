<?php
//load startup scripts
include("config.php" );
include("control.php");
$is_home=1;
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Home</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta http-equiv="content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/helpfit.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<body class="Site">
	<!-- start of header -->
	<?php 
	if(isset($_SESSION['id']) && $_SESSION['id'] > 0){
		include("headerAfterLog.php");
	} else{
		include("header.php"); 
	}
	?>
	<!-- end of header -->
	<!--slide show-->
	<div>
		<div id="newCarousel" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#newCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#newCarousel" data-slide-to="1"></li>
				<li data-target="#newCarousel" data-slide-to="2"></li>
			</ol>
			<div class="carousel-inner">
				<div class="item active">
					<!-- we are using div instead of <img> tag because we can benefit from background-size: cover attribute of a background picture to resize image in a responsive way -->
					<div class="img" style="background-image: url('img/gym1.png');"></div></div>
				<div class="item">
					<!-- we are using div instead of <img> tag because we can benefit from background-size: cover attribute of a background picture to resize image in a responsive way -->
					<div class="img" style="background-image: url('img/gym2.jpg');"></div></div>
				<div class="item">
					<!-- we are using div instead of <img> tag because we can benefit from background-size: cover attribute of a background picture to resize image in a responsive way -->
					<div class="img" style="background-image: url('img/gym3.jpg');"></div></div>
			</div>
		<a class="left carousel-control" href="#newCarousel" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#newCarousel" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
			<span class="sr-only">Next</span>
		</a>
		</div>
	</div>

	<!--content-->
	<div class="container marginTB">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<h3>What is HELPFit?</h3>
				<p>HELPFit is simply best gym you can be subscribed to. HELPFit practice has been the people's best choice since the 1500s, when an a new member or trainer type HELPFit, it is a known brand and user already know HELPFit deliver the bests. It has survived not only five centuries, but also the leap into current age, remaining essentially unchanged principles. It was popularised in the 1960s with the release of fitness machines.</p>
				<?php 
				//check if user is logged in, and show proper message
				if(!(isset($_SESSION['id'])) || (isset($_SESSION['id'])&&$_SESSION['id']) == 0){
					echo "<h3>Join us now for</h3>
					<!-- description list usage -->
					<dl>
					  <dt>Free membership for 14 days</dt>
					  <dd>comes with meal plan</dd>
					  <dd>comes with workout plan</dd>
					</dl>

					<a href=\"signup.php\" class=\"btn btn-success btn-lg btn-block\">Sign Up</a>";
				} else {
					if(isset($user) && $user['type'] == "member"){
						$countDown = (14*86400) - (time() - $user['signupDate']);
						echo "<h3>Thank You for join us</h3>
						<dt>Free membership : ".date("j", $countDown)." days left</dt>
						<dd>After join any session, please go to counter and get your meal plan.</dd>
						<dd>Work out plan will giving by the trainer personally.</dd>";
					} else if(isset($user) && $user['type'] == "trainer"){
						echo "<h3>Thank You for join us</h3>
						<p>Now you add new training sessions and then wait for members to come and join your event.</p>";
					}
				}
				?>
				
			</div>
			<div class="col-xs-12 col-md-6">
				<h3>Why do we use it?</h3>
				<p>It is a long established fact that a member prefer convenience. By using HELPFit online system, member can access to HELPFit gyms and on top of that, they gain convenience of choosing trainers and join classes online.
					<!-- unordered list usage -->
					<ul>
						<li>Single point of fitness classes</li>
						<li>Join training sessions online</li> 
						<li>See history</li>
					</ul>
					HELPFit also:
					<!-- ordered list usage -->
					<ol>
						<li>Many desktop and mobile devices can run it</li>
						<li>It is always accesible</li>
						<li>Provide great customer support.</li>
					</ol></p>
			</div>
		</div>
	</div>

	<!-- start of footer code -->
	<?php include("footer.php"); ?>
	<!-- end of footer -->
	
	
	
	

</body>
</html>