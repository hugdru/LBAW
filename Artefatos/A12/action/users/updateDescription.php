<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['newDescription'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

// 0 Success
$errorMessage = 'Location: ' . $BASE_URL . "pages/users/settings.php" . "?descriptionReply=";

$newDescription = $_POST['newDescription'];

if (updateDescription($_SESSION["idutilizador"], $newDescription))
    $_SESSION["descricao"] = $newDescription;

header($errorMessage . "0");
exit();


?>