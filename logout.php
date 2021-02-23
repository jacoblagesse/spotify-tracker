<?php

	if(!session_start()) {
		header("Location: error.php");
		exit;
	}
	
	session_unset();
	session_destroy();
	
	header("Location: login.php");
	exit;
?>