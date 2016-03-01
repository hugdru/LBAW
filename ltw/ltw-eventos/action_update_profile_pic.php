<?
  session_start();
  include_once('status_messaging.php');

  if (!isset($_POST['desc']) || trim($_POST['desc']) == '') add_error_message('Image Description is mandatory.');

  include_once('database/connection.php'); // connects to the database
  include_once('database/users.php');

  if(isset($_SESSION['error_message'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  else{
    try {
      // Database access
      updateProfilePic($_SESSION['userId'],$_POST['desc']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    header('Location: view_profile.php?userId=' . $_SESSION['userId']);
  }
?>
