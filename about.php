<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Final Project</title>
    <link rel="icon" href="media/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body id="about-body">
    <?php require 'nav.php'; ?>
    <div id="about">
        <h1>About this service</h1>
        <p>This website uses <a href="https://developer.spotify.com/documentation/web-api/">Spotify's API</a> to collect and display your top artists and tracks, based on an affinity value Spotify calculates. According to Spotify:</p>
        <p>"Affinity is a measure of the expected preference a user has for a particular track or artist.  It is based on user behavior, including play history, but does not include actions made while in incognito mode. Light or infrequent users of Spotify may not have sufficient play history to generate a full affinity data set. This data is typically updated once each day for each user."</p>
        <p>You will be asked to authorize this website to use your Spotify data when signing up. You will be redirected to <a href="https://developer.spotify.com/documentation/general/guides/authorization-guide/">Spotify's authorization page</a> to do so securely.</p>
        <p>Something not working? Find a bug? Contact me at <span>jacoblagesse@gmail.com</span></p>
        <iframe src="https://www.youtube.com/embed/bCkq4wHKhcA"></iframe>
    </div>
</body>
</html>
