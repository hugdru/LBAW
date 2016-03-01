<?
  session_start();
  include_once('status_messaging.php');
  if (!isset($_POST['id']) || trim($_POST['id']) == '') add_error_message('Event ID is mandatory.');
  if (!isset($_POST['comment_text']) || trim($_POST['comment_text']) == '') add_error_message('Your comment must have some text...');

  include_once('database/connection.php'); // connects to the database
  include_once('database/events.php');
  
  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{

  	try {
  		// Database Access
  		createThreadComment($_POST['id'],$_POST['idOwner'],$_POST['comment_text']);
  	}
    catch (PDOException $e) {
    	add_error_message($e->getMessage());
    	header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  	header('Location: view_thread.php?id=' . $_POST['id']);
  }
?>
