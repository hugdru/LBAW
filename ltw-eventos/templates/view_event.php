<script src="https://maps.googleapis.com/maps/api/js"></script>
<div id="fb-root"></div>
<script>
// Load Facebook SDK for Javascript
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<section class="event">
  <h1><?=$event['name']?></h1>
  <a href="images/originals/<?=$event['idCover']?>" data-lightbox="image-<?=$event['idCover']?>" data-title="<?=getImageDesc($event['idCover'])?>">
    <img name="eventCover" src="images/covers/<?=$event['idCover']?>">
  </a>
  <ul class="horizontal-bar">
    <li><label>Event Creator</label><a href="view_profile.php?userId=<?=$event['idOwner']?>"><?=$owner['name']?></a></li>
    <li><label><?if($event['visibility'] == 1){echo "Public";}else{echo "Private";}?> event</label></li>
    <li><label>Attendance</label><?
    $intention1 = getIntention1Event($event['idEvent']);
    $intention2 = getIntention2Event($event['idEvent']);
    if(!isset($intention1) || trim($intention2) == '') $intention1 = 0;
    if(!isset($intention2) || trim($intention2) == '') $intention2 = 0;
    ?><div id="number-attending"><?echo $intention1 . ' people going to the event. ' . $intention2 . ' people might go.';?></div></li>
  </ul>
  <ul class="horizontal-bar">
    <li><label>Date</label><time><?=$event['eventDate']?></time></li>
    <li><label>Type</label><span><?=$event['eventType']?></span></li>
    <li><label>Location</label><span><?=$event['location']?></span></li>
    <li><label>Description</label><span><?=$event['description']?></span></li>
  </ul>
  <!-- If the user has not expressed their intention, this must appear -->
  <?$checkInvite = getInvitedEvent($_SESSION['userId'], $event['idEvent']);
  if (!isset($checkInvite["idIE"]) || trim($checkInvite["idIE"]) == '' || $checkInvite['intention'] == 0){?>
    <form id="intention" name="intention" action="action_intention_event.php" method="post">
      <input name="idEvent" type="hidden" value=<?=$event['idEvent']?>>
      <input name="idUser" type="hidden" value=<?=$_SESSION['userId']?>>
      <label id="going">I am going<input name="radio" type="radio" value="1"></label>
      <label id="maybe">I might go<input name="radio" type="radio" value="2"></label>
      <input type="submit" value="Register">
    </form>
    <?}?>
    <script>
    // Google Maps API
    function initialize() {
      //return mapCanvas with jquery
      var mapCanvas = $('#map').get()[0]
      var mapOptions = {
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      var map = new google.maps.Map(mapCanvas, mapOptions)

      //create new geocoder object. all locations are valid geocodes when events are created so there's no need to test for valid geocode here
      var geocoder = new google.maps.Geocoder();

      geocoder.geocode({'address':"<?=$event['location']?>"},
      function(results,status){
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });
      });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <!-- Display the map brought by Google Maps -->
    <div id="map"></div>
    <!-- Display the Facebook Share Button -->
    <div class="fb-share-button" data-href="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" data-layout="box_count"></div>
    <!-- PHOTOS -->
    <div class="section-title">Photos</div>
    <ul class="photo-list">
      <?$photos = getPhotos($event['idEvent']);
      foreach($photos as $photoId){
        ?><li>
          <a href="images/originals/<?=$photoId['idImage']?>" data-lightbox="image-<?=$photoId['idImage']?>" data-title="<?=getImageDesc($photoId['idImage'])?>">
            <img name="<?=$photoId['idImage']?>" src="images/icons/<?=$photoId['idImage']?>">
          </a>
        </li><?
      }?>
    </ul>
    <div class = "section-title"><span>Albums</span></div>
    <!-- ALBUMS -->
    <ul class="album-list">
      <?$albums = getAlbums($event['idEvent']);
      foreach($albums as $alb){
        $photos = getPhotosByAlbum($alb['idAlbum']);
        ?><li><?
        $first=true;
        foreach($photos as $photoId){
          if($first){
          ?>
            <a href="images/originals/<?=$photoId['idImage']?>" data-lightbox="album-<?=$alb['idAlbum']?>">
              <?=$alb['title']?>
            </a>
          <?
          $first=false;
        }
        else{
          ?>
            <a style="display: none" href="images/originals/<?=$photoId['idImage']?>" data-lightbox="album-<?=$alb['idAlbum']?>">
              <?=$alb['title']?>
            </a>
          <?
        }
        }
        ?></li><?
      }?>
    </ul>
    <!-- THREADS AND COMMENTS -->
    <section id="threads" name="threads">
      <div><span>Threads</span></div>
      <?foreach($threads as $thread){?>
        <section class="thread" id="thread_<?=$thread['idEventThread']?>">
          <div class="thread-author"><a href="view_profile.php?userId=<?=$thread['idOwner']?>"><?=getUserNameById($thread['idOwner'])?></a></div>
          <div class="thread-text"><span><?=$thread['threadText']?></span></div>
          <ul class="thread-footer">
            <li><a class="comments-link" name="<?=$thread['idEventThread']?>" href="#thread_<?=$thread['idEventThread']?>">Comments</a></li>
            <li><ul class="comments">
            </ul></li>
            <li id="newCom<?=$thread['idEventThread']?>" class="new-comment" style="display: none">
              <textarea placeholder="Comment" rows="5" required></textarea>
              <button name="<?=$thread['idEventThread']?>" type="button">Create</button>
            </li>
          </ul>
        </section>
        <?}?>
      </section>
      <section class="header-form animated-form">
        <a href="#newThread"><h2 class="form-title">Create New Thread</h2></a>
        <form id="newThread" action="action_create_thread.php" method="post">
          <input name="idEvent" type="hidden" value=<?=$event['idEvent']?>>
          <input name="idOwner" type="hidden" value=<?=$_SESSION['userId']?>>
          <textarea name="text" wrap="hard"></textarea>
          <input type="submit" value="Create">
        </form>
      </section>
    </section>
