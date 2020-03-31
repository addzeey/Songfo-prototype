
<?php
//session_start();
// generate tokens
include 'new.php';
// Prepare an insert statement
if(!is_null($_SESSION['Refresh_Token']) && !is_null($_SESSION['Access_Token'])){
$NewUserInstertSql = "INSERT INTO users (url, access_token, refresh_token, color_pref, created_time	) VALUES (?, ?, ?, ?, ?)";
$unique_url = md5(uniqid(rand(), true));
$checkUrl = "SELECT url FROM users WHERE url = ?";
if($checkUrlStmt = mysqli_prepare($link, $checkUrl)){
    mysqli_stmt_bind_param($checkUrlStmt, "s", $param_checkURL);
    $param_checkURL = $unique_url;
    if(mysqli_stmt_execute($checkUrlStmt)){
        mysqli_stmt_store_result($checkUrlStmt);
        if(mysqli_stmt_num_rows($checkUrlStmt) == 1){
            $param_url = md5(uniqid(rand(), true));
            $unique_url = $param_url;
        } else{
          $param_url = $unique_url;
        }
    } else{
      echo "failed on user select";
        echo "Oops! Something went wrong. Please try again later.";
    }
}
if($NewUserInsterstmt = mysqli_prepare($link, $NewUserInstertSql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($NewUserInsterstmt, "ssssi", $_SESSION["unique-url"], $param_access_token, $param_refresh_token,$param_new_color_pref, $param_current_time);
    // Set parameters
    $_SESSION["unique-url"] = $param_url;
    $param_access_token = $_SESSION['Access_Token']; // Creates a password hash
    $param_refresh_token = $_SESSION['Refresh_Token']; // Creates a password hash
    $param_current_time = time();
    $param_new_color_pref = "null";

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($NewUserInsterstmt)){
        // Redirect to login page
        if($ifErrorExists){
          echo '  <div class="PageWrap"><div id="Authorize"><div id="Header-Title">Songfo!</div><h1>There was an error! Please try again!</h1>';
          echo ' <h2> your unique URL is: <a href ="index.php"></a></h2>';
          echo ' </div></div>';
        }else{
        echo '  <div class="PageWrap"><div id="Authorize"><div id="Header-Title">Songfo!</div><h1>Please Choose your Alert settings!</h1><p>Click the blurred area to copy to your clipboard! Remember this is a unique url to you so dont share it!</p>';
        echo ' <h3> your unique URL is: <a href ="#" data-clipboard-text="'.$site_url.'/url/'.$_SESSION["unique-url"].'" class="copy-url">'.$_SESSION["unique-url"].'</a></h3>';
        echo '<div id="customize-wrap"><form class="customize-form" action="settings/'.$_SESSION["unique-url"].'" method="post"><label class="color-label">Alert Background</label><div class="picker-wrap"><input id="background-color" name="background-color" type="text" class="alert-color-custom" value="#3d1552" /></div>
        <label class="color-label">Alert Border Color</label><div class="picker-wrap"><input id="border-color" name="border-color" type="text" class="alert-color-custom" value="#f8ff00" /></div> <label class="color-label">Alert Label Color</label>
        <div class="picker-wrap"><input id="title-color" name="title-color" type="text" class="alert-color-custom" value="#f8ff00" /></div>
        <label class="color-label">Song Text Color</label><div class="picker-wrap"><input id="song-color" name="song-color" type="text" class="alert-color-custom" value="#000000" /></div>';
        echo '</form><div id="song-wrap-preview">
        <div id="album-cover-preview"><img id="album-image-preview" src="assets/imgs/alert-preview-img.jpg"></div><div id="artist-name-preview"><span id="artist-name-title-preview">Artist</span><h1>Darude</h1></div><div id="track-name-preview"><span id="track-name-title-preview">Track</span><h1>Sandstorm</h1></div>
        </div><div id="btn-submit">Save Settings</div></div></div></div>';
        }
    } else{
      //echo "failed inserting";
      //print_r($_SESSION);
        echo "Something went wrong. Please try again later.";
    }
}
// Close statement
mysqli_stmt_close($NewUserInsterstmt);
mysqli_close($link);
}else{
  echo '  <div class="PageWrap"><div id="Authorize"><div id="Header-Title">Songfo!</div><h1>There was an error! Please try again!</h1>';
  echo ' <h2><a href ="index.php">Click to try again</a></h2>';
  echo ' </div></div>';
}
 ?>
 <script src='<?php echo $site_url; ?>/js/spectrum.js'></script>
<link rel='stylesheet' href='<?php echo $site_url; ?>/css/spectrum.css' />
<script>
$("#btn-submit").click(function(){
 console.log("i did something");
 $(".customize-form").submit();
});
$("#background-color").spectrum({
 clickoutFiresChange: true
});
$("#border-color").spectrum({
 clickoutFiresChange: true,
});
$("#title-color").spectrum({
 clickoutFiresChange: true,
});
$("#song-color").spectrum({
 clickoutFiresChange: true,
});
$("#background-color").on('change.spectrum', function(e, tinyColor) {
 var hexVal = tinyColor.toHexString();
 var color_a = document.getElementById('song-wrap-preview');
  $("#background-color").val(hexVal);
  color_a.style.backgroundColor = hexVal;

});
$("#border-color").on('change.spectrum', function(e, tinyColor) {
 var hexVal = tinyColor.toHexString();
 var color_b_a = document.getElementById('artist-name-preview');
 var color_b_b = document.getElementById('track-name-preview');
 var color_b_c = document.getElementById('album-cover-preview');
 var color_b_d = document.getElementById('artist-name-title-preview');
 var color_b_e = document.getElementById('track-name-title-preview');
  $("#border-color").val(hexVal);
    color_b_a.style.borderColor = hexVal;
    color_b_b.style.borderColor = hexVal;
    color_b_c.style.borderColor = hexVal;
    color_b_d.style.backgroundColor = hexVal;
    color_b_e.style.backgroundColor = hexVal;
});
$("#title-color").on('change.spectrum', function(e, tinyColor) {
 var hexVal = tinyColor.toHexString();
 var color_c_a = document.getElementById('artist-name-title-preview');
 var color_c_b = document.getElementById('track-name-title-preview');
  $("#title-color").val(hexVal);
     color_c_a.style.color = hexVal;
     color_c_b.style.color = hexVal;

});
$("#song-color").on('change.spectrum', function(e, tinyColor) {
 var hexVal = tinyColor.toHexString();
 var color_d_a = document.getElementById('artist-name-preview');
 var color_d_b = document.getElementById('track-name-preview');
  $("#song-color").val(hexVal);
     color_d_a.style.color = hexVal;
     color_d_b.style.color = hexVal;

});
  var clipboard = new ClipboardJS('.copy-url');
  clipboard.on('success', function(e) {
      console.log(e);
  });
  clipboard.on('error', function(e) {
      console.log(e);
  });
//$(".basic").show();
</script>
 <script src="https://cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js"></script>
 <script>
   var clipboard = new ClipboardJS('.copy-url');
   clipboard.on('success', function(e) {
       console.log(e);
   });
   clipboard.on('error', function(e) {
       console.log(e);
   });
   </script>
