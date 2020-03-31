<?php
$ch = curl_init();
// Specify the HTTP headers to send.
        //Authorization: Basic <base64 encoded client_id:client_secret>
        $ClientIDSpotify = "CLIENT ID HERE";
        $ClientSecretSpotify = "CLIENT SECRET HERE";
        $authorization = base64_encode ( "{$ClientIDSpotify}:{$ClientSecretSpotify}" );
        $http_headers = array(
                    "Authorization: Basic {$authorization}",
                );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_headers );
curl_setopt( $ch, CURLOPT_POST, true);

$spotify_url = "https://accounts.spotify.com/api/token";
curl_setopt( $ch, CURLOPT_URL, $spotify_url );

  $data['grant_type'] = "authorization_code";
  $data['code'] = $_GET['code'];
  $data['redirect_uri'] = "https://songfo.addz.xyz/index.php";
//}
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
$_SESSION['Refresh_Token'] = $test->refresh_token; //Store Access Token into session
$_SESSION["Creation_Time"] = date("Y-m-d H:i:s"); //Store Refresh Token into sesssion
//$_SESSION["Access_Timeout"] = "valid";
$ifErrorExists = $test->error;
    //if(isset($_GET['code'])){ echo $reload_page;}
?>
