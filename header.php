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
						<li <?php if (isset($is_training)){ ?>class="active"<?php } ?>><a href="Training.php">Sessions</a></li>
						<li <?php if (isset($is_contact)){ ?>class="active"<?php } ?>><a href="contact.php">Contract Us</a></li>
				</ul>
				<ul class="navbar-right">
					<li><a href="LogIn.php" class="btn btn-trans">Log In</a></li>
					<li><a href="SignUp.php" class="btn btn-success">Sign Up</a></li>
				</ul>
			</div>	
	</nav>
</header>
