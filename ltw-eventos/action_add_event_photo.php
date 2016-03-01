<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_POST['id']) || trim($_POST['id']) == '') add_error_message('Event ID is mandatory.');
  if (!isset($_POST['title']) || trim($_POST['title']) == '') add_error_message('Image description is mandatory.');
  include_once('database/connection.php'); // connects to the database
  include_once('database/events.php');

  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
  	// Database access
    try {
      addEventPhoto($_POST['id'],$_POST['title']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    header('Location: view_event.php?id=' . $_POST['id']);
  }
?>
