<?
  session_start();
  include_once('status_messaging.php');
  if(!isset($_SESSION['username'])){
    add_error_message("Not logged in!");
    header('Location: welcome_page.php');
  }
  else{
  include ('templates/header.php');
  include ('templates/create_event.php');
  include ('templates/footer.php');
}
?>
