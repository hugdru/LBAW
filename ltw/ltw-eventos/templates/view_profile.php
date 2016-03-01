<?
function displayEvents($events){
  foreach($events as $event){?>
    <li>
      <a href="view_event.php?id=<?=$event['idEvent']?>">
        <img name="<?=$event['name']?>" src="images/icons/<?=$event['idCover']?>">
        <h4><span><?=$event['name']?></span></h4>
      </a>
    </li>
    <?}
  }

  $fullLimit = 100;
  //return 3 events for each type
  $limit = 3;
  getEvents($user, $createdEvents, $attendedEvents, $attendingEvents, $limit, 0);
  ?>
  <section id="profile">
    <ul class="description">
      <li><div class="image">
        <a href="images/originals/<?=$user['idProfPic']?>" data-lightbox="image-<?=$user['idProfPic']?>" data-title="<?=getImageDesc($user['idProfPic'])?>">
          <img name="profilePic" src="images/icons/<?=$user['idProfPic']?>">
        </a>
      </div></li>
      <li><ul class="information">
        <li><label>Name</label></li>
        <li><p class="name"><?echo $user['name']?></p></li>
        <li><label>Profile description</label></li>
        <li><p><?echo $user['description']?></p></li>
      </ul></li>
    </ul>
    <!-- EVENTS RELATED TO THE USER -->
    <section class="event-list">
      <h2 id="created" class="event-header">Created Events</h2>
      <ul>
        <?displayEvents($createdEvents);?>
      </ul>
      <h2 id="attended" class="event-header">Attended Events</h2>
      <ul>
        <?displayEvents($attendedEvents);?>
      </ul>
      <h2 id="attending" class="event-header">Attending Events</h2>
      <ul><?foreach($attendingEvents as $event){
        $intention;
        if($event['intention'] == 1){
          $intention='Presence confirmed in the event';
        }
        else if($event['intention'] == 2){
          $intention='Maybe going to the event';
        }?>
        <li>
          <a href="view_event.php?id=<?=$event['idEvent']?>">
            <img name="<?=$event['name']?>" src="images/icons/<?=$event['idCover']?>">
            <h4><span><?=$event['name']?></span></h4>
          </a>
          <h4 class="intention"><span><?=$intention?></span></h4>
        </li>
        <?}?>
      </ul>
      <?if($_SESSION['userId'] == $_GET['userId']){
        $invitedEvents=getUserEventsInvited($_SESSION['userId']);?>
        <h2 id="invited" class="event-header">Invited Events</h2>
        <ul>
          <?displayEvents($invitedEvents);?>
        </ul>
    <?}?>
    </section>
  </section>
  <script>
  //used to know if JSON request has already been made for this header
  //and also to know if JSON request has already retrieved with data (not having retrieved yet or retrieving empty array has same effect)
  var used = [];
  //used to retain info on what header is being shown to use on reqListener
  var parent;
  showRemainingEvents = function(e){
    parent = $(e.target);
    if(!used[parent.attr('id')]){
      used[parent.attr('id')] = 1;
      $.ajax("query_user_events.php",
      {
        type: "POST",
        data: {
          limit: "<?=$fullLimit?>",
          offset: "<?=$limit?>",
          viewerId: "<?=$_SESSION['userId']?>",
          userId: "<?=$_GET['userId']?>",
          eventType: parent.html()
        },
        datatype: "JSON",
        success: reqListener,
        error: function(data){
          console.log(data.responseText);
        }
      });
    }
    //makes sure that reqListener already finished and data was retrieved, not to toggle the next header instead of the ul
    if(used[parent.attr('id')] == 2)
    parent.siblings().first().next().first().toggle('fast');
  }

  reqListener = function(dataString){
    data = JSON.parse(dataString);
    var ul = $(document.createElement('ul'));
    for(var i = 0; i < data.length; i++){
      var li = parent.siblings().first().children().first().clone();

      var a = li.children().first();
      a.attr({
        href: 'view_event.php?id=' + data[i].idEvent
      });

      var img = a.children().first();
      img.attr({
        name: data[i].name,
        src: 'images/icons/' + data[i].idCover
      });

      var h4 = a.children().last();

      var span = h4.children().first();
      span.text(data[i].name);

      ul.append(li);
    }
    //only inserts if data was retrieved
    if(data.length){
      ul.insertAfter(parent.siblings().first());
      used[parent.attr('id')] = 2;
    }
  }

  $("h2.event-header").click(showRemainingEvents);
  </script>
