<?
  session_start();

  $loginBlockTimeout = 60 * 1;
  $maxLoginAttempts = 3;

  include_once('status_messaging.php');
  if (!isset($_POST['username'])  || trim($_POST['username']) == '') add_error_message('No username provided.');
  if (!isset($_POST['password'])  || trim($_POST['password']) == '') add_error_message('No password provided.');
  include_once('database/connection.php'); // connects to the database
  include_once('database/users.php');      // loads the functions responsible for the users table

  if(isset($_SESSION['error_message'])){
    header('Location: welcome_page.php');
  }
  else if (($result = validateUser($_POST['username'], $_POST['password'], $maxLoginAttempts, $loginBlockTimeout)) == 1){ // tries to validate user
    $_SESSION['userId'] = getUserId($_POST['username']);
    $_SESSION['username'] = $_POST['username'];// store the username
    add_success_message('Successfully logged in!');
    header("Location: view_profile.php?userId=" . $_SESSION['userId']);
  }
    else if($result == -1){
      add_error_message('Username does not exist.');
      header('Location: welcome_page.php');
    }
      else if($result == -2){
        add_error_message('Invalid password.');
        header('Location: welcome_page.php');
      }
      else{
        add_error_message('Account is blocked, ' . ($loginBlockTimeout - $result['secondsPassed']) . ' seconds until it is unblocked');
        header('Location: welcome_page.php');
      }
    exit(); //guarantees that values on $_SESSION are saved
?>
