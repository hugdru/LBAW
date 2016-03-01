<?
include_once('database/connection.php');
include_once('database/users.php');

// The profile viewer is not the profile owner - Hide private events the viewer is not invited to
if($_POST['userId'] != $_POST['viewerId']){
  switch($_POST['eventType']){
    case 'Created Events':
      $result = getUserVisibleEventsCreated($_POST['userId'], $_POST['viewerId'], $_POST['limit'], $_POST['offset']);
    break;
    case 'Attended Events':
      $result = getUserVisibleEventsAttended($_POST['userId'], $_POST['viewerId'], $_POST['limit'], $_POST['offset']);
    break;
    case 'Attending Events':
      $result = getUserVisibleEventsAttending($_POST['userId'], $_POST['viewerId'], $_POST['limit'], $_POST['offset']);
    break;
  }
}
// The profile viewer is the profile owner - Don't hide private events
else{
  switch($_POST['eventType']){
    case 'Created Events':
      $result = getUserEventsCreated($_POST['userId'], $_POST['limit'], $_POST['offset']);
    break;
    case 'Attended Events':
      $result = getUserEventsAttended($_POST['userId'], $_POST['limit'], $_POST['offset']);
    break;
    case 'Attending Events':
      $result = getUserEventsAttending($_POST['userId'], $_POST['limit'], $_POST['offset']);
    break;
  }
}
echo json_encode($result);
?>
