<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_POST['id']) || trim($_POST['id']) == '') add_error_message('Event ID is mandatory.');

  include_once('database/connection.php'); // connects to the database
  include_once('database/events.php');

  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
    try {
    	// Database access
      deleteEvent($_POST['id']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    add_success_message('Event successfully deleted!');

    header('Location: view_profile.php?userId=' . $_SESSION['userId']);
  }
?>
