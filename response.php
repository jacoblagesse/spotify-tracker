<?php

    // redirected here after authorizing with Spotify
    if(!session_start()) {
		header("Location: error.php");
		exit;
	}

    require_once '../../env.conf';
    $auth_code = $_GET['code'];

    // I found helpful information on using cURL to make server-side requests with PHP here: https://stackoverflow.com/a/2138534
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 
              http_build_query(array(
                  'grant_type' => 'authorization_code',
                  'code' => $auth_code,
                  'redirect_uri' => 'http://ec2-54-227-95-23.compute-1.amazonaws.com/jslct5FinalProject/response.php',
                  'client_id' => $client_id,
                  'client_secret' => $client_secret
              )));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    if (curl_errno($ch)) {
        $_SESSION['error-msg'] = 'cURL error: ' . curl_error($ch);
        header("Location: error.php");
        exit;
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            
            $result = json_decode($server_output);
            
            require_once 'database.php';
            update_record($_SESSION['loggedin'], $result->access_token, $result->refresh_token, $result->expires_in);
        } else {
            $_SESSION['error-msg'] = 'Unexpected HTTP code: ' . $http_code;
            header("Location: error.php");
            exit;
        }
    }

    curl_close($ch);
    
?>