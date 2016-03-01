<?
  session_start();
  include_once('status_messaging.php');
  include_once('database/connection.php'); // connects to the database
  include_once('database/news.php');
  session_destroy();
  session_start();
  add_success_message('Successfully logged out!');
  header('Location: welcome_page.php');
?>
