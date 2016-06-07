<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'database/event.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['idEvent'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/event/view_event.php");
    exit;
}

$idevento = $_POST['idEvent'];
if(deleteEvent($idevento)){
    $_SESSION['success_messages'][] = 'Your event was canceled successfully';
    header('Location: ' . $BASE_URL . "pages/users/profile.php");
    exit();
}

?>