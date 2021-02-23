<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Session Error</title>
	<style>
        body {
            text-align: center;
        }
		.error {
			color: red;
		}
	</style>
</head>
<body>
	<h1 class="error">Error</h1>
    <?php
        if(!session_start()) {
            print "<p>There was an error starting a session.</p>\n";
        } else {
            $error = empty($_SESSION['error-msg']) ? '' : $_SESSION['error-msg']; 
            print "<p>$error</p>\n";
        }
    ?>
    <p>Please contact the system administrator.</p>
</body>
</html>