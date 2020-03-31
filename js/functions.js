//SHOW SONG POPUP
function songVisible() {
    return $( "#song-wrap" ).removeClass("hidden").addClass("visible");
}
// HIDE SONG POPUP
function songHidden() {
    return $( "#song-wrap" ).removeClass("visible").addClass("hidden");
}
// GET SONG INFORMATION FROM SPOTIFY API
function spotifyGet(){
  $.ajax({
     url: 'https://api.spotify.com/v1/me/player/currently-playing?market=ES',
     type: 'GET',
     contentType: 'application/json',
     headers: {
        'Authorization': headerToken
     },
     success: function (result) {
         //console.log(result);
         var counter = 1;
         if (result === undefined){
           item = null;
           current = "nothing";
           //console.log(current);
         }else
         $.each(result.item, function(i, item) {
           //console.log(item);
             if(counter == 1) {
               //console.log(current);
                 //console.log("track name: " + result.item.name);
                 //console.log("artist name: " + item.artists[0].name);
                 //console.log(item.images[1].url);
                 artistName = item.artists[0].name;
                 trackName = result.item.name;
                 albumImage = item.images[1].url
                 current = artistName + trackName + albumImage;
                 //console.log("current inside: " + current);
                 //console.log("previoius inside: " + previous);
                 counter++
           }
         });
         //console.log("Current: " + current);
         //console.log( "Previous: " + previous);
     },
     error: function (error) {
              console.log("error");
     }
  });
}
// REPLACE SONG INFO
function replaceSong(){
  $( '<div id="album-cover"><img id="album-image" src="' + albumImage + '"></img></div>' ).replaceAll( "#album-cover" );
  $( '<div id="artist-name"><h1>' + artistName + '</h1></div>' ).replaceAll( "#artist-name" );
  $( '<div id="track-name"><h1>' + trackName + '</h1></div>' ).replaceAll( "#track-name" );
}
// Get the time passed since the key creation time and convert it into seconds.
function getTimePassed(){
  currentTime = Math.ceil($.now() / 1000);
  return currentTime - keyCreation;
}
// check if the time passed is bigger than 60 minutes, if so reload the page.
function checkRefreshToken() {
  if(getTimePassed() > 60 * 60){
          location.reload();
  }else{
    console.log("The access token is valid" + getTimePassed());
  }
}
