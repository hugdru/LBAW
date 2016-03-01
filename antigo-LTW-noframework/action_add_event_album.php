<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_POST['id']) || trim($_POST['id']) == '') add_error_message('Event ID is mandatory.');
  if (!isset($_POST['title']) || trim($_POST['title']) == '') add_error_message('Album Title is mandatory.');
  include_once('database/connection.php'); // connects to the database
  include_once('database/events.php');

  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
  	// Database access
    try {
      addEventAlbum($_POST['id'],$_POST['title']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    add_success_message('Album added to your event!');
    header('Location: view_event.php?id=' . $_POST['id']);
  }
?>
