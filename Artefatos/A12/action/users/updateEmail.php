<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['newEmail'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

$newEmail = $_POST['newEmail'];

if (updateEmail($_SESSION["idutilizador"], $newEmail))
    $_SESSION["email"] = $newEmail;

$_SESSION['success_messages'][] = 'Email updated successfully';
header('Location: ' . $BASE_URL . "pages/users/settings.php");
exit();


?>

?>