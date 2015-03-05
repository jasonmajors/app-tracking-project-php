<?php 	
	require 'header.inc.php'; 
    require 'EmailWrap.class.php';


	if ($_SESSION['app_submitted']): ?>
		<?php
			EmailWrap::sendEmail('jasonrmajors@gmail.com', 'Confirmation', 'Hello World!'); 
		?>

		<div class='centered'>
			<h5>Thank you for your interest in employment with Company!</h5>
			<br><a href=<?php echo $PATH . "/applicant.php"; ?>>Return to Open Positions<a>
		</div>	
		<?php unset($_SESSION['app_submitted']); ?>
	<?php else: ?>
		<h1>404 Back up</h1>
	<?php endif; ?>	
<?php require 'footer.php'; ?>	