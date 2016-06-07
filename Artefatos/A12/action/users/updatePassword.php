<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['csrf'])) {
    $_SESSION['error_messages'][] = 'An unauthorized request was made';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (!isset($_POST['idutilizador'], $_POST['password'], $_POST['newPassword'], $_POST['newRepeatPassword'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

$idutilizador = $_POST['idutilizador'];
$password = $_POST['password'];
$newPassword = $_POST['newPassword'];
$newRepeatPassword = $_POST['newRepeatPassword'];

$redirect = 'Location: ' . $BASE_URL . "pages/users/settings.php";

if ($idutilizador != $_SESSION['idutilizador']) {
    $_SESSION['error_messages'][] = 'ERROR - Password update: You can only change your password';
    header($redirect);
    exit();
}

if ($newPassword != $newRepeatPassword) {
    $_SESSION['error_messages'][] = 'ERROR - Password update: New passwords mismatch';
    header($redirect);
    exit();
}

$newPasswordLength = strlen($newPassword);
if ($newPasswordLength < 8 || $newPasswordLength > 100) {
    $_SESSION['error_messages'][] = 'ERROR - Password update: New password should have between 8 and 100 characters';
    header($redirect);
    exit();
}

if (!validLoginDatabaseCheck($idutilizador, $password)) {
    $_SESSION['error_messages'][] = 'ERROR - Password update: Original password is incorrect';
    header($redirect);
    exit();
}

$hashedNewPassword = create_hash($newPassword);

if (updatePassword($idutilizador, $hashedNewPassword) === false) {
    throw new RuntimeException('Failed to move uploaded file.');
}

$_SESSION['success_messages'][] = 'Password updated successfully';
header($redirect);
exit();
?>
