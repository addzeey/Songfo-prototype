<?php
$ch = curl_init();
// Specify the HTTP headers to send.
        //Authorization: Basic <base64 encoded client_id:client_secret>
        $ClientIDSpotify = "63885791a4fa42569d872a4addff922c";
        $ClientSecretSpotify = "a1a6e9b89ca34fdc892493a57e41a234";
        $authorization = base64_encode ( "{$ClientIDSpotify}:{$ClientSecretSpotify}" );
        $http_headers = array(
                    "Authorization: Basic {$authorization}",
                );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_headers );
curl_setopt( $ch, CURLOPT_POST, true);

$spotify_url = "https://accounts.spotify.com/api/token";
curl_setopt( $ch, CURLOPT_URL, $spotify_url );
// *************************************************
// HERE'S WHERE I CORRECTLY SPECIFY THE GRANT TYPE
// *************************************************
  $data['grant_type'] = "refresh_token";
  $data['refresh_token'] = $_SESSION['Refresh_Token'];
//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response_json = curl_exec( $ch );
curl_close($ch);
// CONVERT JSON OUTPUT TO VARIABLES
//print_r($response_json);
//echo "<br>";
//print_r($data);
$test = json_decode($response_json);
$_SESSION['Access_Token'] = $test->access_token; //Store Access Token into session
$_SESSION["Access_Timeout"] = "valid";
$_SESSION["Creation_Time"] = date("Y-m-d H:i:s"); //Store Refresh Token into sesssion
$ifErrorExists = $test->error;
      //$_SESSION["Creation_Time"] = date("Y-m-d H:i:s"); //Store Refresh Token into sesssion
  //  if(isset($_GET['code'])){ echo $reload_page;}



  $updateTokens = "UPDATE users SET access_token = ?, created_time = ? WHERE url = ?";
  $updateTokenStmt = mysqli_prepare($link, $updateTokens);
      // Bind variables to the prepared statement as parameters
      $param_update_url = $_GET['url'];
      $param_update_access_token = $test->access_token; // Creates a password hash
      $param_update_current_time = time();
      mysqli_stmt_bind_param($updateTokenStmt, "sis", $param_update_access_token, $param_update_current_time, $param_update_url);
      // Set parameters
      // Attempt to execute the prepared statement
      mysqli_stmt_execute($updateTokenStmt);
          // Redirect to login page';
  // Close statement
  ?>
