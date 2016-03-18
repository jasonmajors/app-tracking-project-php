<?php
namespace Jason;

class Authenticate
{
	public static function login_required_redirect($url)
    {	
    	$redirect_path = PATH . $url;
    	if (self::checkIfAuthenticated() === false) {
            $_SESSION['Auth_Required_Msg'] = "Login required.</br>";
            return header("Location: $redirect_path");
        }
    }

    public static function admin_required_redirect($url)
    {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
            $_SESSION['Auth_Required_Msg'] = "Admin rights required. </br>";
            return header("Location: $redirect_path");
        }
    }

    private function checkIfAuthenticated()
    {
    	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			return true;
		} else {
			return false;
		}
    }

    public static function redirect_user($url)
    {
    	if (self::checkIfAuthenticated()) {
    		$redirect_path = PATH . $url;
    		return header("Location: $redirect_path");
    	}
    }
    // In progress...
    public function login()
    {
        $inputs = array('username' => 'Open', 'password');
        $validator = new Validator($fields);

    }
}