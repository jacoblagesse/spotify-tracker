<?php

    if(!session_start()) {
		header('Location: error.php');
		exit;
	}

    $loggedIn = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];
    $data = empty($_SESSION['data']) ? 'none' : $_SESSION['data'];
    $stored_data = empty($_SESSION['stored_data']) ? false : $_SESSION['stored_data'];

    if (!$loggedIn) {
        header('Location: login.php');
    }

    if (!$stored_data) {
        require 'database.php';
        $auth_codes = get_auth_codes($loggedIn);
        $access_token = $auth_codes[0];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Spotify Tracker</title>
    <link rel="icon" href="media/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/8e2e26f8e6.js" crossorigin="anonymous"></script>
    <script src="scripts/query.js"></script>
</head>
    <body>
        <?php require 'nav.php'; ?>
        <div id="featured">
            <img src='media/spotify-logo.png' id='im1' class='featured-image' alt='First featured image'/>
            <img src='media/spotify-logo.png' id='im2' class='featured-image' alt='Second featured image'/>
            <img src='media/spotify-logo.png' id='im3' class='featured-image' alt='Third featured image'/>
            <h1 id="featured-title">Your latest tastes.</h1>
            <form id="change-timeframe" class="form-inline">
                <label>From the past </label>
                <select id="timeframe" class="button-green" name="timeframe">
                      <option value="0" selected>1 month</option>
                      <option value="1">6 months</option>
                      <option value="2">3+ years</option>
                </select>
            </form>
            <div class="arrow bounce"><i class="fa fa-angle-down fa-5x" aria-hidden="true"></i></div>
        </div>
        <div id="list-container">
            <div id="artists" class="display-column">
                <h1>Your top artists.</h1>
            </div>
            <div id="tracks" class="display-column">
                <h1>Your top tracks.</h1>
            </div>
        </div>
        <script>
            var is_stored = '<?php echo $stored_data ?>';
            var log = <?php echo json_encode($data[2]) ?>;
            (is_stored && console.log(JSON.parse(log)));

            $(function(){
                checkStored();

                $('#timeframe').change(function(){
                    console.log('input');
                    changeTimeframe($('#timeframe').val());
                });
            });
            function checkStored() {
                if (is_stored) {
                    var artists = <?php echo json_encode($data[0]) ?>;
                    var tracks = <?php echo json_encode($data[1]) ?>;
                    buildFromStored(artists, tracks);
                    console.log('Data from session');
                } else {
                    startQuery();
                }
            }
            function startQuery() {
                var access_token = '<?php echo $access_token; ?>';
                queryAPI(access_token, 'artists', 'short_term', 0, true);
                queryAPI(access_token, 'tracks', 'short_term', 1, true);
                queryAPI(access_token, 'artists', 'medium_term', 2);
                queryAPI(access_token, 'tracks', 'medium_term', 3);
                queryAPI(access_token, 'artists', 'long_term', 4);
                queryAPI(access_token, 'tracks', 'long_term', 5);
            }
            function changeTimeframe(range) {
                var access_token = '<?php echo $access_token; ?>';
                var artists;
                var tracks;
                switch(parseInt(range)) {
                    case 0:
                        artists = <?php echo json_encode($data[0]) ?>;
                        tracks = <?php echo json_encode($data[1]) ?>;
                        break;
                    case 1:
                        artists = <?php echo json_encode($data[2]) ?>;
                        tracks = <?php echo json_encode($data[3]) ?>;
                        break;
                    case 2:
                        artists = <?php echo json_encode($data[4]) ?>;
                        tracks = <?php echo json_encode($data[5]) ?>;
                        break;
                }
                $('#artists').empty();
                $('#tracks').empty();
                buildFromStored(artists, tracks);
                console.log('Data from session');
            }
        </script>
    </body>
</html>
