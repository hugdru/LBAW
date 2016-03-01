<?
session_start();
include_once('status_messaging.php');
include_once('database/connection.php');
include_once('database/users.php');
include_once('database/events.php');

if(isset($_SESSION['userId'])){
  if(getEventByID($_GET['eventId'])){
    if($_SESSION['userId'] == getOwnerID($_GET['eventId'])){
      if(getUserById($_GET['userId'])){
        if($_GET['userId'] != $_SESSION['userId']){
          if(!getInvitedEvent($_GET['userId'], $_GET['eventId'])){

            try {
            	// Database access
              createInvitedEvent($_GET['userId'], $_GET['eventId'], 0);
            }
            catch (PDOException $e) {
            add_error_message($e->getMessage());
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            add_success_message('Successfully invited user');
            header("Location: view_event.php?id=" . $_GET['eventId']);
          }
          else{
            add_error_message('User was already invited to this event');
            header("Location: view_event.php?id=" . $_GET['eventId']);
          }
        }
        else{
          add_error_message('You may not invite yourself to your event.');
          header("Location: view_event.php?id=" . $_GET['eventId']);
        }
      }
      else{
        add_error_message('User does not exist');
        header("Location: view_event.php?id=" . $_GET['eventId']);
      }
    }
    else{
      add_error_message('You are unable to invite people to this event');
      header("Location: view_event.php?id=" . $_GET['eventId']);
    }
  }
  else{
    add_error_message('Event does not exist');
    header("Location: view_profile.php?userId=" . $_SESSION['userId']);
  }
}
else{
  add_error_message('Not logged in!');
  header("Location: view_event.php?id=" . $_GET['eventId']);
}
?>
