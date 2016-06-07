<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'database/event.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['intention'], $_POST['idEvent'])) {
    $_SESSION['error_messages'][] = 'Parameters missing. Something went wrong.';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$idutilizador = $_SESSION["idutilizador"];
$idevento = $_POST['idEvent'];

if(insertParticipation($idevento, $idutilizador)){
    $_SESSION['success_messages'][] = 'Your comment was posted successfully';
    header('Location: ' . $BASE_URL . "pages/event/view_event.php?id=" . $idevento );
    exit();
}
else{
    $_SESSION['error_messages'][] = 'Something went wrong.';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

?>