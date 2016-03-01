<?
session_start();
include_once('status_messaging.php');
include_once('database/connection.php');
include_once('database/users.php');

// Database access
try {
	updateUserDescription($_SESSION['username'], $_POST['newDescription']);
}
catch (PDOException $e) {
    add_error_message($e->getMessage());
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

add_success_message('Description changed!');
header('Location: view_profile.php?userId=' . $_SESSION['userId']);
exit();
?>
