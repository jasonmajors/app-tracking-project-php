<?php
	require 'header.inc.php';
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/classes/Database.class.php';
    require __DIR__ . '/classes/FormValidate.class.php';
    require __DIR__ . '/classes/Authenticate.class.php';
	
    // Redirects users already logged in.
	$auth = new Authenticate();
	$auth->redirect_user("/employer.php");

	if (isset($_SESSION['Auth_Required_Msg'])) {
		$login_error = $_SESSION['Auth_Required_Msg'];
		echo "<label class='error-text'>$error_msg</label>";
	}

	function login_user($completed_form)
	{
		$username = $completed_form['username'];
		$inputted_password = $completed_form['password'];
		// Sprinkle the salt.
		$salted_password = $inputted_password . $username;
		$password_db = '';

		$db_connect = new Database();
		$user_array = $db_connect->getTable('users', 'Username', $username);
		$db_connect = null;

		if (!empty($user_array)) {
			// The assoc. array will always only have 1 user array inside since usernames are unique.
			// Get hashed & salted pw from the db.
			$password_db = $user_array[0]['Password'];	
			$firstname = $user_array[0]['FirstName'];	
		}

		if (password_verify($salted_password, $password_db)) {
			$_SESSION['loggedin'] = true;
			$_SESSION['username'] = $username;
			$_SESSION['firstname'] = $firstname;
			// Default value for Admin column in db is false.
			$admin = $user_array[0]['Admin'];
			$_SESSION['admin'] = $admin;
			
			if (isset($_SESSION['Auth_Required'])) {
				unset($_SESSION['Auth_Required']);
			}
			
			return header("Location: " . $PATH . "employer.php"); 

		} else {
			$login_error = "Invalid login information";
			return $login_error;
		}
	}

	function main()
	{
		// Must be the names of the form inputs.
		$fields = array("username" => "Open", "password" => "Open");
		$validate = new FormValidate($fields);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Returns associative array of form field => value
			$completed_form = $validate->get_form_data();	
			if ($completed_form) {
				// Logs the user in or returns a string "invalid login info" error.
				$invalid_login_error = login_user($completed_form);
				
				return $invalid_login_error;

			} else {
				// Get errors keyed by input fields due to the field being incomplete or not valid
				$form_incomplete_error = $validate->get_errors();

				return $form_incomplete_error;
			}	
		}
	}
		
	function echo_error_msg($error, $field)
	{
		if (isset($error[$field])) {
			$error_msg = ucfifrst($error[$field]);
			echo "<label class='error-text'>$error_msg</label>";
		}
	}

	$error = main();
?>
<div class="container">
		<form class="form-signin" method="POST" action="login.php">
			<h1 class="form-signin-heading">Login</h1>
			<!-- Check if the error is the "Invalid login info" string; incomplete/invalid form errors will be a dictionary keyed by the input field -->
				<?php if (gettype($error) == 'string') {
					echo "<label class='form-signin-heading jm-error'>$error</label>";
				} ?>
			<!-- Display error msg for 'username' not being populated -->
			<?php if (isset($error['username'])) {
				$error_msg = ucfirst($error['username']);
				echo "<label for='username' class='form-singin-heading jm-error'>$error_msg</label>";
			} ?>
			<label for="username" class="sr-only">Username:</label> 
			<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>

			<!-- Display error msg for 'password' not being populated -->
			<?php if (isset($error['password'])) {
				$error_msg = ucfirst($error['password']);
				echo "<label for='password' class='form-singin-heading jm-error'>$error_msg</label>";
			} ?>

			<label for="password" class="sr-only">Password:</label> 
			<input type="password" class="form-control" name="password" placeholder="Password" required>

			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>	
</div>
<?php require 'footer.php'; ?>

