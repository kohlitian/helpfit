<header>
		<nav class="navbar-style navbar-default" id="nav">
				<div class="navbar-header">
					<a class="navbar-brand" href="Home.php"><img class="logo" src="img/helpfitlogo.png" alt="Help Fit Logo"></a>
					<button type="button" class="navbar-toggle tabMobile" data-toggle="collapse" data-target="#navToggle" id="tabMobile">
						<span class="icon-bar"></span>
	        			<span class="icon-bar"></span>
	        			<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="navToggle">
					<ul class="nav navbar-nav">
						<li <?php if (isset($is_home)){ ?>class="active"<?php } ?>><a href="Home.php">Home</a></li>
						<li <?php if (isset($is_training)){ ?>class="active"<?php } ?>><a href="Training.php">Jobs</a></li>
						<li <?php if (isset($is_contact)){ ?>class="active"<?php } ?>><a href="contact.php">Contract Us</a></li>
					</ul>
					<ul class="navbar-right">
						<li><a href="myTraining.php" class="btn btn-success">
							<?php if ($user['type']=='member'){ ?>Training History<?php } else { ?>My Training<?php } ?></a></li>
						<li><a href="#" class="btn btn-primary btn-user"><i class="fa fa-user-circle-o"></i> <?php echo $_SESSION['username'] ?> <i class="fa fa-caret-down"></i></a>
						<?php 
						//check if user is member or trainer and show proper menu
						if($_SESSION['id']>0 && $user['type'] == "member"){
							echo"<ul>
								<li><a href=\"Training.php?status=available\">Available Trainings</a></li>
								<li><a href=\"modifyMember.php\">Edit Account Info</a></li>
								<li><a href=\"logOut.php\">Logout</a></li>

							</ul></li>
						</ul>";
						} else if($_SESSION['id'] > 0 && $user['type'] == "trainer"){
							echo "<ul>
								<li><a href=\"newTraining.php\">Add new training</a></li>
								<li><a href=\"modifyTrainer.php\">Edit Account Info</a></li>
								<li><a href=\"logOut.php\">Logout</a></li>

							</ul></li>
						</ul>";
						}
					?>
				</div>	
		</nav>
	</header>
