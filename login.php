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
	
	if ($action == 'do_login') {
		handle_login();
	} else {
		login_form();
	}

    function handle_login() {
		$username = empty($_POST['username']) ? '' : $_POST['username'];
		$password = empty($_POST['password']) ? '' : $_POST['password'];
        
        require 'database.php';

        $attempt_result = login($username, $password);
        
        switch($attempt_result) {
            case 1:
                $error = 'Incorrect username or password';
                require "login_form.php";
                exit;
            case 2:
                $error = 'Database error: Please contact the system administrator.';
                require "login_form.php";
                exit;
            default:
                $_SESSION['loggedin'] = $username;
                header("Location: trends.php");
        }
	}
	
	function login_form() {
		$username = "";
		$error = "";
		require "login_form.php";
        exit;
	}
?>