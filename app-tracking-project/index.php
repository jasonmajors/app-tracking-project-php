<?php require 'header.inc.php'; ?>
		
<?php unset($_SESSION); session_destroy(); ?>
<div id="container">
	<div class="centered">
		<h3>JASON'S APPLICANT TRACKING SYSTEM</h3>
		<h3><a href=<?php echo $PATH . "/employer.php"; ?>>Employer</a></h3>
		<h3><a href=<?php echo $PATH . "/applicant.php"; ?>>Applicants</a></h3>
	</div>
	<?php require 'footer.php'; ?>	
</div>	
	

