<?
session_start();
include_once('status_messaging.php');

// Coming to change password from somewhere unexpected
if (strpos($_SERVER['HTTP_REFERER'], 'change_password.php') !== 0){
    session_destroy();
		session_start();
		add_error_message('Arrived at change password from outside.');
		header('Location: welcome_page.php');
		exit();
	}

include_once('database/connection.php');
include_once('database/users.php');

$passwordMinSize = 6;

// Confirmation password doesn't match
if($_POST['passwordNew'] != $_POST['passwordNewConfirm']){
	add_error_message('Password confirmation invalid.');
}
else{
	if(strlen($_POST['passwordNew']) >= $passwordMinSize){
		// Database access
		if(changeUserPassword($_SESSION['username'], $_POST['password'], $_POST['passwordNew'])){
			add_success_message('Password changed!');
			header('Location: view_profile.php?userId=' . $_SESSION['userId']);
			exit();
		}
		else
			add_error_message('Old password invalid.');
	}
	else
		add_error_message('Please enter a password with atleast ' . $passwordMinSize . ' characters');
}
?>
<script>
	window.history.go(-1);
</script>
<?
exit();
?>
