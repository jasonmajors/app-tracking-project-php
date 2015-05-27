<?php 	
	require 'header.inc.php'; 
    require __DIR__ . '/classes/EmailWrap.class.php';


	if ($_SESSION['app_submitted']): ?>
		<?php
			//EmailWrap::sendEmail('jasonrmajors@gmail.com', 'Confirmation', 'Hello World!'); 
		?>

		<div class='container'>
			<div class='jumbotron'>
			<h2 class='text-center'>Thank you for applying!</h2>
			<p class='text-center'>
				<a class='btn btn-lg btn-primary' role='button' href=<?php echo $PATH . "/applicant.php"; ?>>Return to Open Positions</a>
			</p>
		</div>	
		<?php //unset($_SESSION['app_submitted']); ?>
	<?php else: ?>
		<h1>404</h1>
	<?php endif; ?>	
<?php require 'footer.php'; ?>	