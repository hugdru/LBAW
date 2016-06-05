<?php
include_once('../../config/init.php');
include_once($BASE_DIR . "database/admin.php");

if (!$_POST['admin_username'] || !$_POST['admin_password']) {
    $_SESSION['error_messages'][] = 'Invalid login';
    $_SESSION['form_values'] = $_POST;

    $_SESSION['alert'] = 'Username or Password not specified';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$admin_username = $_POST['admin_username'];
$admin_password = $_POST['admin_password'];

if (authenticate($admin_username, $admin_password)) {
    $_SESSION['admin_username'] = $admin_username;
    $_SESSION['admin_password'] = $admin_password;
    $_SESSION['success_messages'][] = 'Login successful';

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    $_SESSION['error_messages'][] = 'Login failed';
    $_SESSION['alert'] = 'Please verify your credentials.';

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>

