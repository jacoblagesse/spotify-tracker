<?php 
    if(!session_start()) {
		header("Location: error.php");
		exit;
	}

    session_unset();
    $loggedIn = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];
	
	if ($loggedIn) {
		header("Location: response.php");
		exit;
	}

    $action = empty($_POST['action']) ? '' : $_POST['action'];
	
	if ($action == 'do_signup') {
		handle_signup();
	} else {
		signup_form();
	}

    function handle_signup() {
		$username = empty($_POST['username']) ? '' : $_POST['username'];
		$password = empty($_POST['password']) ? '' : $_POST['password'];
        
        $confirm_password = empty($_POST['confirm-password']) ? '' : $_POST['confirm-password'];
        
        if (!$username || !$password || !$confirm_password) {
            $error = 'All fields are required!';
            require "signup_form.php";
            exit;
        } else if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
            require "signup_form.php";
            exit;
        } else {
        
            require 'database.php';
            $attempt_result = create_record($username, $password);

            switch($attempt_result) {
                case 1:
                    $error = 'Username already taken.';
                    require "signup_form.php";
                    exit;
                case 2:
                    $error = 'Database error: Please contact the system administrator.';
                    require "signup_form.php";
                    exit;
                default:
                    require '../../env.conf';
                    $_SESSION['loggedin'] = $username;
                    header("Location: https://accounts.spotify.com/authorize?client_id=$client_id&response_type=code&redirect_uri=http://ec2-54-227-95-23.compute-1.amazonaws.com/jslct5FinalProject/response.php&scope=user-top-read");
                    exit;
            }
        }
	}
	
	function signup_form() {
		$username = "";
		$error = "";
		require "signup_form.php";
        exit;
	}

?>