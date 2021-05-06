<script type="text/javascript" src = "js/jquery.js"></script>
<script type="text/javascript" src = "js/bootstrap.min.js"></script>
<script type="text/javascript" src = "js/bootbox.min.js"></script>
<?php 
//if rating is needed, include it
if (isset($need_rating)){ ?>
<script type="text/javascript" src = "js/star-rating.min.js"></script>
<?php } 
//if helpfit.js is allowed, included it
echo $no_helpfit_js;
if (!isset($no_helpfit_js)) { ?>
<script type="text/javascript" src = "js/helpfit.js"></script>

<?php echo $no_helpfit_js; } ?>

<?php 

//if there is any message to show to user, popup it
if ($passThruMessage!=''){ ?>
<script>
	window.setTimeout(function(){
	bootbox.alert('<?php echo addslashes($passThruMessage); ?>');
	},100);
</script>
<?php }

//at end of all pages, check if database is connected, and close it's connection
if (isset($connect)&&$connect)
	mysqli_close($connect);
?>

<footer class="container-fluid">
		<div class="footer">
			<div class="container marginTB">
				<div class="row">
					<div class="col-xs-7 col-sm-offset-1"  style="line-height: 17pt;">
						HELPFit, Revolution of Fitness
						<small class="hidden-xs"><br><?php

						//count statistics of users and member and trainings and print them
						if ($connect){ echo @mysqli_fetch_array(mysqli_query($connect,"select count(*) from member;"))[0]; ?> members, <?php echo @mysqli_fetch_array(mysqli_query($connect,"select count(*) from trainers;"))[0]; ?> trainers, <?php echo @mysqli_fetch_array(mysqli_query($connect,"select count(*) from trainingsessions;"))[0]; ?> sessions <?php } ?></small>
					</div>
					<div class="col-sm-4 col-xs-5">
						<p>Copyright © 2017 HELPFit<br/>
						<small>All Rights Reserved<span class="hidden-xs hidden-sm"> for Armin Nikdel & Koh Li Tian</span></small></p>
					</div>
				</div>
			</div>	
		</div>	
	</footer>

<!-- Put scripts at end of the page, so they don't slow down loading time -->
