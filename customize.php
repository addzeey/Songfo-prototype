<?php
$User_URL = $_GET['settings'];
if(!empty($_POST)){
$json = json_encode($_POST);
$updateSettings = "UPDATE users SET color_pref = ? WHERE url = ?";
$updateSettingsStmt = mysqli_prepare($link, $updateSettings);
    // Bind variables to the prepared statement as parameters
    $param_get_url = $_GET['settings'];
    $param_update_color_pref = $json;
    mysqli_stmt_bind_param($updateSettingsStmt, "ss", $param_update_color_pref, $param_get_url);
    // Set parameters
    // Attempt to execute the prepared statement
    mysqli_stmt_execute($updateSettingsStmt);
      mysqli_close($link);
        $colorJSON = $json;
        $alertColors = json_decode($colorJSON);
        $alert_bg_color = $alertColors->{'background-color'};
        $alert_border_color = $alertColors->{'border-color'};
        $alert_title_color = $alertColors->{'title-color'};
        $alert_song_color = $alertColors->{'song-color'};

        echo '  <div class="PageWrap"><div id="Authorize"><div id="Header-Title">Songfo!</div><h1>Please Choose your Alert settings!</h1><p>Click the blurred area to copy to your clipboard! Remember this is a unique url to you so dont share it!</p>';
        echo ' <h3> your unique URL is: <a href ="" data-clipboard-text="'.$site_url.'/url/'.$User_URL.'" class="copy-url">'.$User_URL.'</a></h3>';
        echo '<div id="customize-wrap"><form class="customize-form" action="'.$site_url.'/settings/'.$User_URL.'" method="post"><label class="color-label">Alert Background</label><div class="picker-wrap"><input id="background-color" name="background-color" type="text" class="alert-color-custom" value="'.$alert_bg_color.'" /></div>
        <label class="color-label">Alert Border Color</label><div class="picker-wrap"><input id="border-color" name="border-color" type="text" class="alert-color-custom" value="'.$alert_border_color.'" /></div> <label class="color-label">Alert Label Color</label>
        <div class="picker-wrap"><input id="title-color" name="title-color" type="text" class="alert-color-custom" value="'.$alert_border_color.'" /></div>
        <label class="color-label">Song Text Color</label><div class="picker-wrap"><input id="song-color" name="song-color" type="text" class="alert-color-custom" value="'.$alert_song_color.'" /></div>';
        echo '</form><div id="song-wrap-preview">
        <div id="album-cover-preview"><img id="album-image-preview" src="assets/imgs/alert-preview-img.jpg"></div><div id="artist-name-preview"><span id="artist-name-title-preview">Artist</span><h1>Darude</h1></div><div id="track-name-preview"><span id="track-name-title-preview">Track</span><h1>Sandstorm</h1></div>
        </div><div id="btn-submit">Save Settings</div></div></div></div>';
}else{
  //$json = json_encode($_POST);
  $fetch_color_settings = "SELECT access_token, refresh_token, color_pref, created_time  FROM users WHERE url = ?";
  if($get_color_stmt = mysqli_prepare($link, $fetch_color_settings)){
      mysqli_stmt_bind_param($get_color_stmt, "s", $param_url);
      $param_url = $_GET['settings'];
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($get_color_stmt)){
        mysqli_stmt_store_result($get_color_stmt);
          if(mysqli_stmt_num_rows($get_color_stmt) == 1){
            mysqli_stmt_bind_result($get_color_stmt, $fetch_access_token, $fetch_refresh_token, $fetch_color_pref,  $fetch_created_time);
            if(mysqli_stmt_fetch($get_color_stmt)){
                $colorJSON = $fetch_color_pref;
              }else{
                $colorJSON = "";
              }} else{

            }}else{
            echo "No User found with that url.";
          }} else{
          echo "Oops! Something went wrong. Please try again later.";
      }
        mysqli_close($link);
          $alertColors = json_decode($colorJSON);
          $alert_bg_color = $alertColors->{'background-color'};
          $alert_border_color = $alertColors->{'border-color'};
          $alert_title_color = $alertColors->{'title-color'};
          $alert_song_color = $alertColors->{'song-color'};
          echo '  <div class="PageWrap"><div id="Authorize"><div id="Header-Title">Songfo!</div><h1>Please Choose your Alert settings!</h1><p>Click the blurred area to copy to your clipboard! Remember this is a unique url to you so dont share it!</p>';
          echo ' <h3> your unique URL is: <a href ="" data-clipboard-text="'.$site_url.'/url/'.$User_URL.'" class="copy-url">'.$User_URL.'</a></h3>';
          echo '<div id="customize-wrap"><form class="customize-form" action="'.$site_url.'/settings/'.$User_URL.'" method="post"><label class="color-label">Alert Background</label><div class="picker-wrap"><input id="background-color" name="background-color" type="text" class="alert-color-custom" value="'.$alert_bg_color.'" /></div>
          <label class="color-label">Alert Border Color</label><div class="picker-wrap"><input id="border-color" name="border-color" type="text" class="alert-color-custom" value="'.$alert_border_color.'" /></div> <label class="color-label">Alert Label Color</label>
          <div class="picker-wrap"><input id="title-color" name="title-color" type="text" class="alert-color-custom" value="'.$alert_border_color.'" /></div>
          <label class="color-label">Song Text Color</label><div class="picker-wrap"><input id="song-color" name="song-color" type="text" class="alert-color-custom" value="'.$alert_song_color.'" /></div>';
          echo '</form><div id="song-wrap-preview">
          <div id="album-cover-preview"><img id="album-image-preview" src="assets/imgs/alert-preview-img.jpg"></div><div id="artist-name-preview"><span id="artist-name-title-preview">Artist</span><h1 id="song-color">Darude</h1></div><div id="track-name-preview"><span id="track-name-title-preview">Track</span><h1 id="song-color">Sandstorm</h1></div>
          </div><div id="btn-submit">Save Settings</div></div></div></div>';
}
echo '<style>#album-cover-preview{border: 1px solid '.$alert_border_color.';}
  #artist-name-preview, #track-name-preview {border: 1.3px solid'.$alert_border_color.'; color: '.$alert_song_color.';}
  #track-name-title-preview, #artist-name-title-preview{color: '.$alert_title_color.'; background-color: '.$alert_border_color.';}
  #song-wrap-preview { background-color: '.$alert_bg_color.';}</style>'




?>
<script src='<?php echo $site_url; ?>/js/spectrum.js'></script>
<link rel='stylesheet' href='<?php echo $site_url; ?>/css/spectrum.css' />
<script src="https://cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js"></script>
<script>
var alertBgColor = "<?php echo $alert_bg_color; ?>";
var alertSongColor = "<?php echo $alert_song_color; ?>";
var alertTitleColor = "<?php echo $alert_title_color; ?>";
var alertBorderColor = "<?php echo $alert_border_color; ?>";
$("#btn-submit").click(function(){
 console.log("i did something");
 $(".customize-form").submit();
});
$("#background-color").spectrum({
 clickoutFiresChange: true,
  color: alertBgColor
});
$("#border-color").spectrum({
 clickoutFiresChange: true,
  color: alertBorderColor
});
$("#title-color").spectrum({
 clickoutFiresChange: true,
  color: alertTitleColor
});
$("#song-color").spectrum({
 clickoutFiresChange: true,
  color: alertSongColor
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
  </script>
