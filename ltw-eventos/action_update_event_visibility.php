<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_POST['visibility']) || trim($_POST['visibility']) == '') add_error_message('Visibility is mandatory.');
  if (!isset($_POST['id']) || trim($_POST['id']) == '') add_error_message('Event ID is mandatory.');

  include_once('database/connection.php'); // connects to the database
  include_once('database/events.php');

  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
    try {
      // Database access
      updateEventVisibility($_POST['id'],$_POST['visibility']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    header('Location: view_event.php?id=' . $_POST['id']);
  }
?>
