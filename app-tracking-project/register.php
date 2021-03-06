<?php
    require 'header.inc.php';
    
    use Jason\Validator;
    use Jason\Authenticate;
    use Jason\Database;
    // TODO: Create a main function to clean this up.
    Authenticate::admin_required_redirect('/login.php');

    // Must be the names of the form inputs.
    $db_connect = new Database();
    $fields = array("firstname" => "text", "lastname" => "text", "username" => "text", "password" => "text");

    $validate = new Validator($fields);
    // Checks for POST request.
    $completed_form = $validate->get_form_data();

    function hash_pw($username, $password)
    {   
        // Salt
        $salted_pw = $password . $username;
        $hashed_password = password_hash($salted_pw, PASSWORD_DEFAULT);
        
        return $hashed_password;
    }

    function register_user($completed_form)
    {
        $username = $completed_form['username'];

        $db_connect = new Database();
        // Returns an array of every Username in the user table.
        $usernames = $db_connect->getDistinctValues('users', 'Username');

        if (!in_array($username, $usernames)) {
            $password = $completed_form['password'];
            $firstname = $completed_form['firstname'];
            $lastname = $completed_form['lastname'];

            $hashed_password = hash_pw($username, $password);
            $db_connect->register($firstname, $lastname, $username, $hashed_password);
            $db_connect = null;
            
        } else {
            echo "<div class='error-msg'>Username taken.</div>";
        }
    }

    if ($completed_form) {
        register_user($completed_form);
    }

?>
<!-- Registration Form -->
<div class='centered-form'>
    <h2>Register</h2>
    <form method="POST" action="register.php">
        First Name: <input type="text" name="firstname" /></br>
        Last Name: <input type="text" name="lastname" /></br>
        Username: <input type="text" name="username" /></br>
        Password: <input type="password" name="password" /></br>
        <input type="submit" value="Register"></br>
    </form> 
</div>

<?php require 'footer.php'; ?>