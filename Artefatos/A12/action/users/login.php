<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');

if (validLoginSessionCheck()) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (!$_POST['username'] || !$_POST['password']) {
    $_SESSION['error_messages'][] = 'Invalid login';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
    $_SESSION['success_messages'][] = 'Login successful';
    header('Location: ' . $BASE_URL . "pages/event/explore_events.php");
} else {
    $_SESSION['error_messages'][] = 'Login failed';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit();
?>
