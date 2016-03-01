<ul class="navigation">
  <li><a href="#" id="inviteUsers">Invite users</a></li>
  <li><a href="update_event_cover.php?id=<?=$_GET['id']?>">Update cover</a></li>
  <li><a href="update_event_description.php?id=<?=$_GET['id']?>">Change description</a></li>
  <li><a href="#" id="changeVisibility">Change visibility</a></li>
  <li><a href="update_event_date.php?id=<?=$_GET['id']?>">Change date</a></li>
  <li><a href="add_event_photos.php?id=<?=$_GET['id']?>">Add a photo</a></li>
  <li><a href="add_album.php?id=<?=$_GET['id']?>">Add album</a></li>
  <li><a href="delete_event.php?id=<?=$_GET['id']?>">Delete event</a></li>
</ul>
<form  id="changeVisibilityForm" style="display: none" action="action_update_event_visibility.php" method="post">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <input name="visibility" type="radio"
    <?if($event['visibility'] == 1){?>
        checked="checked"<?}?>
        value="1">Public
  <input name="visibility" type="radio"
    <?if($event['visibility'] == 0){?>
        checked="checked"<?}?>
      value="0">Private
  <input type="submit" value="Update">
</form>

<script>
// Change the event viibility on the fly... with style!
showChangeVisibility = function(){
  $('#changeVisibilityForm').toggle('fast');
}
$('#changeVisibility').click(showChangeVisibility);
</script>

<form id="searchUForm" class="find-user-list" action="" style="display: none">
  <input type="textarea" name="searchUsers" rows="1" cols="20"></textarea>
  <ul id="queryUser">
  </ul>
  <h4 id="moreResults">Show more results</h4>
</form>
<script>
//keeps data from last ajax call, as opposed to query_user_events on view_profile, that makes a new call
//to try different implementations
var data;

//maxUsersDisplayed
var maxUsersDisplayed = 3;
showInviteUsers = function(){
  $('#searchUForm').toggle("fast");
}
$('#inviteUsers').click(showInviteUsers);

showMoreUsers = function(){
  if($('#queryUser > li').length != data.length)
  addResults(maxUsersDisplayed, data.length);
}
$('#moreResults').click(showMoreUsers);
//queries Users using name regex
queryUsers = function(){
  $.ajax("query_users.php",
  {
    type: "POST",
    data: {queryText: $(this).val(),
      userId: "<?=$_SESSION['userId']?>",
      eventId: "<?=$_GET['id']?>"
    },
    datatype: "JSON",
    success: reqListener,
    error: function(data){
      console.log(data.responseText);
    }
  });
}

addResults = function(startIndex, maxIndex){
  for(var i = startIndex; i < maxIndex; i++){
    if(!data[i])
    break;
    var element, a, img, h4, span;
    // Already displaying users --> startIndex > 0
    if(i > 0){
      element = $('#queryUser').children().first().clone();
      a = element.children().first();
      img = a.children().first();
      h4 = a.children().last();
      span = h4.children().first();
    }
    // No users already displaying --> let's create them!
    else{
      element = $(document.createElement('li'));
      img = $(document.createElement('img'));
      h4 = $(document.createElement('h4'));
      a = $(document.createElement('a'));
      span = $(document.createElement('span'));
    }

    // Change the attributes of the used elements
    img.attr({
      name: data[i].name,
      src: 'images/icons/' + data[i].idProfPic
    });

    a.attr({
      href: 'action_invite_user.php?userId=' + data[i].idUser + '&eventId=' + "<?=$_GET['id']?>"
    });

    span.text(data[i].name);

    h4.append(span);

    a.append(img);
    a.append(h4);
    element.append(a);
    $('#queryUser').append(element);
  }
}

reqListener = function(dataString){
  //deletes previous search results
  $('#queryUser').empty();

  data = JSON.parse(dataString);

  addResults(0, maxUsersDisplayed);
}
$(document).ready(function(){
  $('input[type=textarea]').bind('input propertychange', queryUsers);
});
</script>
