<?php
    
    if(!session_start()) {
		header("Location: error.php");
		exit;
	}

    // creates record with user/pass
    function create_record($username, $password) {
        require '../../env.conf';
        
        $result;
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
			require "signup_form.php";
            exit;
        }
        
        $sql1 = "SELECT id FROM users WHERE username = '$username'";
        $queryResult = $db->query($sql1);

        if ($queryResult) {
            $match = $queryResult->num_rows;
            $queryResult->close();
            
            if ($match === 0) {
                
                $password = sha1($password);
                $sql2 = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

                if ($db->query($sql2) === TRUE) {
                    $result = 0;
                } else {
                    $result = 2;
                }
                
            } else {
                $result = 1;
            }
        } else {
            $result = 2;
        }
        
        $db->close();
        return $result;
    }

    function login($username, $password) {
        require '../../env.conf';
        
        $result;
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if ($db->connect_error) {
            $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
			require "login_form.php";
            exit;
        }
        
        $username = $db->real_escape_string($username);
        $password = $db->real_escape_string($password);
        
        $password = sha1($password); 

		$sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
		
		$queryResult = $db->query($sql);

        if ($queryResult) {
            $match = $queryResult->num_rows;
            $queryResult->close();
            
            if ($match == 1) {
                $result = 0;
            } else {
                $result = 1;
            }
        } else {
            $result = 2;
        }
        
        $db->close();
        return $result;
    }

    // updates record with Spotify API info
    function update_record($username, $access_token, $refresh_token, $expire_time) {
        require '../../env.conf';
        
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            $_SESSION['error-msg'] = "Connection failed: " . $db->connect_error;
            header("Location: error.php");
            exit;
        }
        
        date_default_timezone_set("America/Chicago");
        $last_refreshed = date("Y-m-d H:i:s");
        
        $sql = "UPDATE users SET access_token='$access_token', refresh_token='$refresh_token', expire_time='$expire_time', last_refreshed='$last_refreshed' WHERE username='$username'";
        
        if ($db->query($sql) === TRUE) {
            header("Location: trends.php");
        } else {
            $_SESSION['error-msg'] = "Error: " . $sql . "<br>" . $db->error;
            header("Location: error.php");
            exit;
        }
        
        $db->close();
    }

    // given a username, querys API auth codes from database. Refreshes tokens if needed.
    function get_auth_codes($username) {
        require '../../env.conf';
        
        $result;
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
			require "login_form.php";
            exit;
        }
        
        $sql1 = "SELECT access_token, refresh_token, expire_time, last_refreshed FROM users WHERE username = '$username'";
        
        $queryResult = $db->query($sql1);

        if ($queryResult) {
            $match = $queryResult->num_rows;
            
            if ($match === 1) {
                $record = $queryResult->fetch_assoc();      
                date_default_timezone_set('America/Chicago');
                $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($record['last_refreshed']));
                if ($diff > $record['expire_time']) {
                    refresh_tokens($record['refresh_token']);
                    $get_auth_code($username);
                } else {
                    return array($record['access_token'], $record['refresh_token']);
                }
                
            } else {
                $result = 1;
            }
        } else {
            $result = 2;
        }
        
        $queryResult->close();
        $db->close();
        return $result;
    }

    // querys Spotify's API with refresh token to get new access token (and sometimes possibly a new refresh token as well? Documentation is ambiguous)
    function refresh_tokens($refresh_token) {
        require '../../env.conf';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 
                  http_build_query(array(
                      'grant_type' => 'refresh_token',
                      'refresh_token' => $refresh_token,
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
                $new_refresh_token = empty($result->refresh_token) ? $refresh_token : $result->refresh_token;
                update_record($_SESSION['loggedin'], $result->access_token, $new_refresh_token, $result->expires_in);
                
            } else {
                $_SESSION['error-msg'] = 'Unexpected HTTP code when refreshing: ' . $http_code . ' ' . $server_output . ' ' . $refresh_token;
                header("Location: error.php");
                exit;
            }
        }
        curl_close($ch);
    }
?>