<?php
	function logout()
	{
		include 'settings.php';
		session_start();
		unset($_SESSION);
		session_destroy();

		return header("Location: $PATH");
	}

	logout()
?>
