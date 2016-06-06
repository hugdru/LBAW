<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['newCountry'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

// 0 Success
$errorMessage = 'Location: ' . $BASE_URL . "pages/users/settings.php" . "?countryReply=";

$newCountry = $_POST['newCountry'];

if (updateCountry($_SESSION["idutilizador"], $newCountry)){
    $_SESSION["idpais"] = $newCountry;
    $_SESSION["pais"] = getCountryById($newCountry);
}

header($errorMessage . "0");
exit();


?>