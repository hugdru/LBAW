<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'database/event.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['newComment'], $_POST['idEvent'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/event/view_event.php");
    exit;
}

$idutilizador = $_SESSION["idutilizador"];
$texto = $_POST['newComment'];
$idevento = $_POST['idEvent'];

if(insertComment($texto, $idutilizador, $idevento)){
    $_SESSION['success_messages'][] = 'Your comment was posted successfully';
    header('Location: ' . $BASE_URL . "pages/event/view_event.php?id=" . $_POST["idEvent"]);
    exit();
}


?>