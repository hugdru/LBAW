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

// 0 Success

$errorMessage = 'Location: ' . $BASE_URL . "pages/users/settings.php" . "?emailReply=";

$newEmail = $_POST['newEmail'];

if (updateEmail($_SESSION["idutilizador"], $newEmail))
    $_SESSION["email"] = $newEmail;

header($errorMessage . "0");
exit();


?>

?>