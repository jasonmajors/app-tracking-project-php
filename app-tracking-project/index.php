<?php require 'header.inc.php'; ?>
		
<?php unset($_SESSION); session_destroy(); ?>
<div class="container">
	<div class="jumbotron">
		<h1>App Tracking Demo</h1>
		<p class="lead">A basic applicant tracking system demonstration</p>
		<p>
			<a class="btn btn-lg btn-primary" href=<?php echo PATH . "/employer.php"; ?> role="button">Employer</a>
			<a class="btn btn-lg btn-primary" href=<?php echo PATH . "/applicant.php"; ?> role="button">Apply!</a>
		</p>
	</div>
</div>	
<?php require 'footer.php'; ?>

