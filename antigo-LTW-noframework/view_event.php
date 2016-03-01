<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_GET['id'])) add_error_message("No ID");

  if(isset($_SESSION['error_message']) && !isset($_SESSION['createThreadError'])){
    unset($_SESSION['createThreadError']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{

  include_once('database/connection.php');
  include_once('database/events.php');
  include_once('database/users.php');

  // Database access - get Event
  try {
    $event = getEventByID($_GET['id']);
    if ($event === false) add_error_message("No such event");
  } catch (PDOException $e) {
    add_error_message($e->getMessage());
  }

  include ('templates/header.php');

  // Database access - check if Event Owner is the same as the logged in user
  try {
    $ownerID = getOwnerID($_GET['id']);
    if($ownerID == $_SESSION['userId']){
      // The user is the event owner and can edit the event
      include ('templates/edit_event.php');
    }
  } catch (PDOException $e) {
    add_error_message($e->getMessage());
  }

  // Database access - check Event Owner
  try {
    $owner = getUserById($ownerID);
    if ($owner === false) add_error_message("No such user");
  } catch (PDOException $e) {
    add_error_message($e->getMessage());
  }

  // Database access - check Threads for the Event
  try {
    $threads = getEventThreads($_GET['id']);
    if ($threads === false) $threads=[];
  } catch (PDOException $e) {
    add_error_message($e->getMessage());
  }

  if(isset($_SESSION['error_message']) && !isset($_SESSION['createThreadError'])){
    unset($_SESSION['createThreadError']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
    include ('templates/view_event.php');
    include ('templates/footer.php');
  }
}
?>
