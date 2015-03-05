<?php
	require "/opt/lampp/htdocs/password_compat/lib/password.php";

	$password = 'mypassword';
	$hash = password_hash($password, PASSWORD_DEFAULT);
	$password = 'myguess';

	if (password_verify($password, $hash))
	{
		echo 'success!';
	}
	else
	{
		echo 'denied!';
	}
?>