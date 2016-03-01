<?
include_once('status_messaging.php');
session_start();
if (!isset($_POST['name'])  || trim($_POST['name']) == '') add_error_message('No name provided.');
if (!isset($_POST['username'])  || trim($_POST['username']) == '') add_error_message('No username provided.');
if (!isset($_POST['password'])  || trim($_POST['password']) == '') add_error_message('No password provided.');
if (!isset($_POST['confirm_password'])  || trim($_POST['confirm_password']) == '') add_error_message('No password confirmation provided.');
include_once('database/connection.php'); // connects to the database
include_once('database/users.php');      // loads the functions responsible for the users table
$passwordMinSize = 6;
if(!isset($_SESSION['error_message'])){
  if($_POST['password'] != $_POST['confirm_password']){
    add_error_message('Password confirmation invalid.');
    ?>
      <script>
      window.history.go(-1);
      </script>
    <?
  }
  else // Password has less than minimum size
  if(strlen($_POST['password']) < $passwordMinSize){
    add_error_message('Please enter a password with atleast ' . $passwordMinSize . ' characters');
  }
  else // Username already exists
  if(usernameExists($_POST['username'])){
    add_error_message('Username already in use.');
  }
  else{
  	// Database access
    createUser($_POST['name'],$_POST['username'],$_POST['password']);
    $_SESSION['userId'] = getUserId($_POST['username']);
    $_SESSION['username'] = $_POST['username'];// store the username
    add_success_message('Account created!');
    header('Location: view_profile.php?userId=' . $_SESSION['userId']);
    exit();
  }
}
?>
  <script>
  window.history.go(-1);
  </script>
<?
?>
