<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/applicant_tracking.css" rel="stylesheet">
    	<link rel="icon" type="image/png" href="media/haneyface.png">
    	<style type="text/css" title="currentStyle">
    		@import "js/DataTables/media/css/demo_table.css";
		</style>
		<link href="js/jquery-ui-1.11.0.custom/jquery-ui.css" rel="stylesheet">
		<title>Jason's PHP ATS</title>
	</head>
	<body>
	<?php 
		session_start();
		include 'settings.php';
		$loggedin = false;
		$admin = false;

		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        	$firstname = $_SESSION['firstname'];
        	$admin = $_SESSION['admin'];
        	$loggedin = true;
        }
	?>	
	<!-- Nav bar -->
	<div id="navbar">
		<ul id="left-links">
			<?php if ($loggedin): ?>
				<li><a href=<?php echo $PATH . "/employer.php"; ?>>Welcome, <?php echo $firstname; ?></a></li>
			<?php else: ?>
				<li>Welcome!<li>
			<?php endif; ?>
		</ul>	
		<ul id="right-links">	
			<?php if ($admin): ?>
			<li><a href=<?php echo $PATH . "/register.php"; ?>>Add User</a></li>
			<?php endif; ?>
			<?php if ($loggedin): ?>
			<li><a href=<?php echo $PATH . "/logout.php"; ?>>Logout</a></li>
			<?php endif; ?>	
			<!-- FOR DEMO PURPOSE ON BLOG -->
			<li><a href="/projects">Return to Projects</a></li>
		</ul>	
	</div>
