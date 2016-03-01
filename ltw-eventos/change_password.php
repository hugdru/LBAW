<?
session_start();
include_once('status_messaging.php');
if(!isset($_SESSION['userId'])){
  add_error_message("Not logged in!");
  header("Location: welcome_page.php");
  exit();
}

include_once('templates/header.php');
include_once('database/connection.php');
include_once('database/users.php');
include_once('templates/change_password.php');
include_once('templates/footer.php');

?>
