<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn('Location: ' . $_SERVER['HTTP_REFERER']);

$text = $_GET['text'];

$result = null;
if ($text) {
    $result = getMyEvents($_SESSION['idutilizador'], $_GET['text']);
} else {
    $result = getMyEventsSimple($_SESSION['idutilizador']);
}

if (!$result) {
    return null;
}
echo $result;
?>