<?
session_start();
include_once('status_messaging.php');
if (!isset($_POST['idEvent'])  || trim($_POST['idEvent']) == '') add_error_message('Event ID is mandatory.');
if (!isset($_POST['idOwner'])  || trim($_POST['idOwner']) == '') add_error_message('User ID is mandatory.');
if (!isset($_POST['text'])  || trim($_POST['text']) == '') add_error_message('Your thread needs to have some text...');

include_once('database/connection.php'); // connects to the database
include_once('database/events.php');

if(isset($_SESSION['error_message'])){
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else{
  if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['text'])){
  add_error_message('Thread title can only contain letters and spaces');
  $_SESSION['createThreadError'] = 1;
  }
  else{
    try {
      // Database access
      createEventThread($_POST['idEvent'],$_POST['idOwner'],$_POST['text']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }
      header('Location: view_event.php?id=' . $_POST['idEvent']);
}
?>
