<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

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

// 0 Success, 1 New Passwords mismatch, 2 Authentication failure

$errorMessage = 'Location: ' . $BASE_URL . "pages/users/settings.php" . "?passwordReply=";

if ($idutilizador != $_SESSION['idutilizador']) {
    header($errorMessage . "1");
    exit();
}

if ($newPassword != $newRepeatPassword) {
    header($errorMessage . "2");
    exit();
}

$newPasswordLength = strlen($newPassword);
if ($newPasswordLength < 8 || $newPasswordLength > 100) {
    header($errorMessage . "4");
    exit();
}

if (!validLoginDatabaseCheck($idutilizador, $password)) {
    header($errorMessage . "3");
    exit();
}

$hashedNewPassword = create_hash($newPassword);

updatePassword($idutilizador, $hashedNewPassword);

header($errorMessage . "0");
exit();
?>
