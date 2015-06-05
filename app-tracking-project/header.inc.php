<?php include 'settings.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/applicant_tracking.css" rel="stylesheet">
    	<!-- load signin.css only on the login.php page -->
    	<?php if ($_SERVER["REQUEST_URI"] == $PATH . "/login.php") {
    		echo '<link href="css/signin.css" rel="stylesheet">';
    	} ;?>
    	<!-- load the narrow jumbotron css on index and success.php -->
    	<?php if ($_SERVER["REQUEST_URI"] == $PATH . "/" || $_SERVER["REQUEST_URI"] == $PATH . "/success.php") {
    		echo '<link href="css/jumbotron.css" rel="stylesheet">';
    	} ;?>

    	<?php if ($_SERVER["REQUEST_URI"] == $PATH . "/employer.php") {
    		echo '<link href="css/dashboard.css" rel="stylesheet">';
    	} ;?>

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
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<?php if ($loggedin): ?>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
          		</button>
					<span class="navbar-brand">Welcome, <?php echo $firstname; ?></span>
				<?php else: ?>
					<a class="navbar-brand" href=<?php echo $PATH; ?>>Applicant Tracking Demo</a>
				<?php endif; ?>	
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">	
					<?php if ($admin): ?>
						<li><a href=<?php echo $PATH . "/register.php"; ?>>Add User</a></li>
					<?php endif; ?>
					<?php if ($loggedin): ?>
						<li><a href=<?php echo $PATH . "/employer.php"; ?>>Dashboard</a></li>
						<li><a href=<?php echo $PATH . "/logout.php"; ?>>Logout</a></li>
					<?php endif; ?>	
				</ul>
			</div>	
		</div>
	</nav>
	