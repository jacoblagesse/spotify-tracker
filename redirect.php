<?php
    
    if(!session_start()) {
		header("Location: error.php");
		exit;
	}

    session_unset();
    
    require '../../env.conf';
    require 'database.php';

    $_SESSION['loggedin'] = $testuser;
    header("Location: trends.php");
    

?>