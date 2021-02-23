<?php
    // To reduce API requests, stores response in session

    if(!session_start()) {
		header("Location: error.php");
		exit;
	}
    
    $loggedIn = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];
	
	if (!$loggedIn) {
		header("Location: login.php");
		exit;
	}

    $data = empty($_POST['data']) ? '' : $_POST['data'];
//    $tracks = empty($_POST['tracks']) ? '' : $_POST['tracks'];
    
    $_SESSION['data'] = $data;
    //$_SESSION['tracks'] = $tracks;
    $_SESSION['stored_data'] = true;
?>