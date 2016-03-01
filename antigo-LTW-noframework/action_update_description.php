<?
session_start();
include_once('status_messaging.php');
include_once('database/connection.php');
include_once('database/users.php');

//prevents Persistent XSS on user description
if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['description']))
	add_error_message('Description can only contain letters and spaces');?>
	<script>
	window.history.go(-1);
	</script>
<?}
else{
try{
	// Database access
	updateUserDescription($_SESSION['username'], $_POST['description']);
}
catch (PDOException $e) {
    add_error_message($e->getMessage());
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

header('Location: view_profile.php?userId=' . $_SESSION['userId']);
}
exit();
?>
