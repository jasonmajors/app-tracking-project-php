<?php
	class Authenticate
	{
		public function login_required_redirect($url, $admin_only=false)
	    {	
	    	include 'settings.php';
	    	$redirect_path = $PATH . $url;
	    	if ($admin_only) {
	    		// Default value for admin is 0.
	    		if (!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
	    			$_SESSION['Auth_Required_Msg'] = "Admin rights required. </br>";
	    			return header("Location: $redirect_path");
	    		}
	    	}
	    	elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	            $_SESSION['Auth_Required_Msg'] = "Login required.</br>";
	            return header("Location: $redirect_path");
	        }
	    }

	    public function authenticated()
	    {
	    	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true) {
				return true;
			} else {
				return false;
			}
	    }

	    public function redirect_user($url)
	    {
	    	include 'settings.php';
	    	$loggedin = $this->authenticated();
	    	if ($loggedin) {
	    		$redirect_path = $PATH . $url;
	    		return header("Location: $redirect_path");
	    	}
	    }
	}