<?
session_start();
include_once('status_messaging.php');
if (!isset($_POST['idUser']) || trim($_POST['idUser']) == '') add_error_message('User ID is mandatory.');
if (!isset($_POST['idEvent']) || trim($_POST['idEvent']) == '') add_error_message('Event ID is mandatory.');
if (!isset($_POST['radio']) || trim($_POST['radio']) == '') add_error_message('User intention is mandatory.');

include_once('database/connection.php'); // connects to the database
include_once('database/events.php');

if(isset($_SESSION['error_message'])){
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else{
  try {
    // Database access
    $checkInvite = getInvitedEvent($_SESSION['userId'], $_POST['idEvent']);
    if (!isset($checkInvite["idIE"])){
      createInvitedEvent($_POST['idUser'], $_POST['idEvent'], $_POST['radio']);
    }
    else{
      updateInvitedEvent($checkInvite['idIE'],$_POST['radio']);
    }
  }
  catch (PDOException $e) {
    add_error_message($e->getMessage());
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  header('Location: view_event.php?id=' . $_POST['idEvent']);
}
?>
