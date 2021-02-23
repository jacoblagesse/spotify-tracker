<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Database Signup</title>
    <link rel="icon" href="media/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php require 'nav.php'; ?>
    <div id="login-container" class="login">
        <h1 id="login-header">Create account.</h1>
        <p>(For use with only this website, don't use your Spotify credentials!)</p>
        
        <?php
            if ($error) {
                print "<p class='error-message'>$error</p>\n";
            }
        ?>
        
        <p id="error"></p>
        <form action="signup.php" method="POST">
            <input type="hidden" name="action" value="do_signup">
            <input type="text" id="username" name="username" class="login" placeholder="Username" autofocus value="<?php print $username; ?>">
            <input type="password" id="password" name="password" class="login" placeholder="Password">
            <input type="password" id="confirm-password" name="confirm-password" class="login" placeholder="Confirm password">
            <input id="login-submit" class="button-green" type="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="login_form.php">Sign in</a> instead.</p>
    </div>
</body>
</html>