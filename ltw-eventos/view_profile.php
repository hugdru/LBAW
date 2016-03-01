<?
session_start();
include_once('status_messaging.php');
if (!isset($_SESSION['userId'])) add_error_message("Not logged in!");

if(isset($_SESSION['error_message'])){
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else{

include_once('database/connection.php');
include_once('database/users.php');

try {
  $user = getUserById($_GET['userId']);
  if ($user === false)
  	add_error_message("No such user");
} catch (PDOException $e) {
  add_error_message($e->getMessage());
}

if(isset($_SESSION['error_message'])){
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else{

include ('templates/header.php');
// Check if Profile viewer is the Profile owner
if($_SESSION['userId'] == $_GET['userId'])
  include ('templates/edit_profile.php');
include ('templates/view_profile.php');
include ('templates/footer.php');
}
}
?>
