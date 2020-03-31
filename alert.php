<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo $site_url; ?>/css/styles.css">
<?php
require 'config/functions.php';
unset($_SESSION['Access_Token']);
unset($_SESSION["Creation_Time"]);
$url = $_GET['url'];
$Current_time = time();
$GetUserSql = "SELECT access_token, refresh_token, color_pref, created_time  FROM users WHERE url = ?";
if($stmt = mysqli_prepare($link, $GetUserSql)){
    mysqli_stmt_bind_param($stmt, "s", $param_url);
    $param_url = $url;
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if email exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $fetch_access_token, $fetch_refresh_token, $fetch_color_pref,  $fetch_created_time);
            if(mysqli_stmt_fetch($stmt)){
                $_SESSION['Refresh_Token'] = $fetch_refresh_token;
                $Key_creation = $fetch_created_time;
                $Alert_color_pref = $fetch_color_pref;
                $Key_Time_Passed = $Current_time - $Key_creation;
                      if ($Key_Time_Passed > 60 * 60) {
                        $_SESSION['Access_Token'] = "expired";
                        $_SESSION["Access_Timeout"] = "expired";
                          }else{
                            $_SESSION["Access_Timeout"] = "valid";
                            $_SESSION['Access_Token'] = $fetch_access_token;
                            $SetAlertStatus = "start";
                          }}
            }else{
              echo '<h1> OOPS! LOOKS LIKE THIS URL DOES NOT EXIST! </h1>';
              echo $url;
              unset($_SESSION['Access_Token']);
              unset($_SESSION["Creation_Time"]);
              $SetAlertStatus = "stop";
            }
      } else{
            echo "No User found with that url.";
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
mysqli_stmt_close($stmt);

      if ($_SESSION['Access_Token'] == "expired" && $_SESSION["Access_Timeout"] == "expired") {
        include 'refresh.php';
        echo '<script> location.reload(); </script>';
        echo '<script> console.log("The token was refreshed");</script>';
          }else{
        echo '<script> console.log("Token is fine");</script>';
            }
            mysqli_close($link);
      $colorJSON = $Alert_color_pref;
      $alertColors = json_decode($colorJSON);
      $alert_bg_color = $alertColors->{'background-color'};
      $alert_border_color = $alertColors->{'border-color'};
      $alert_title_color = $alertColors->{'title-color'};
      $alert_song_color = $alertColors->{'song-color'};
?>
<style>
#album-cover {
  border: 1px solid <?php echo $alert_border_color; ?>;
}
#artist-name, #track-name  {
  border: 1.3px solid <?php echo $alert_border_color; ?>;
}
#artist-name::before, #track-name::before {
    color: <?php echo $alert_title_color; ?>;
    background-color: <?php echo $alert_border_color; ?>;
}
#artist-name h1, #track-name h1 {
  color: <?php echo $alert_song_color; ?>;
}
#song-wrap {
    background-color: <?php echo $alert_bg_color; ?>;
}
</style>
<script src="<?php echo $site_url; ?>/js/functions.js"></script>
<script>
//console.log("<?php echo $Key_Time_Passed; ?>")
//console.log("Access: <?php echo $_SESSION['Access_Token']; ?>");
//console.log(" Refresh: <?php echo $_SESSION['Refresh_Token']; ?>");
//console.log(" Time: <?php echo $_SESSION["Access_Timeout"] ; ?>");
var token = "<?php echo $_SESSION['Access_Token']; ?>";
var headerToken = "Bearer " + token;
var tokenTimePassed ="";
var currentTime ="";
var keyCreation = "<?php echo $Key_creation; ?>";
var alertStatus = "<?php echo $SetAlertStatus; ?>";
var previous = null;
var current = null;
var artistName = null;
var trackName = null;
var albumImage = null;
var html = '';
/////// set how long you want the popup to show in seconds
var hideSong = 7;
//**********************************************************
// CHECK SPOTIFY API FOR SONG INFORMATION EVERY X AMOUNT
//**********************************************************
if(alertStatus == "start"){
  spotifyGet()
  previous = current;
  setInterval(spotifyGet, 10 * 1000);
  ///////////////////////////////////////////////////////
    setTimeout(function(){
      $( '<div id="album-cover"><img id="album-image" src="' + albumImage + '"></img></div><div id="artist-name"><h1>' + artistName + '</h1></div><div id="track-name"><h1>' + trackName + '</h1></div>' ).replaceAll( "#song-inner" );
      if(current == "nothing"){
       }else{songVisible();}
         //hide song after 6 seconds
           setTimeout(songHidden, hideSong * 1000);
          //song has been hidden
     }, 2000);

  setInterval(function() {
      if (previous && current && previous !== current) {
          replaceSong();
          console.log("song info was updated")
          songIsUpdating = true;
          if(current == "nothing"){
            //DO NOTHING
            }else{songVisible();
          }
           setTimeout(songHidden, hideSong * 1000);
      }
      previous = current;
      console.log("song info was checked!")
  }, 5 * 1000);

  // Check refresh token and reload page if it needs to be refreshed
    setInterval(checkRefreshToken, 30 * 1000);
}else{
  songHidden()
}
</script>
<div id="song-wrap" class="hidden">
<div id="song-inner"></div>
</div>
<div id="test" style="background-color: red;">
</div>
