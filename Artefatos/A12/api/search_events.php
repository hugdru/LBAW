<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'database/users.php');
include_once($BASE_DIR . 'functions/users.php');

$text = $_GET['text'];

$result = null;
if ($text) {
    if (validLoginSessionCheck()) {
        $result = getEventsAuthenticated($_SESSION['idutilizador'], $_GET['text']);
    } else {
        $result = getEventsPublic($_GET['text']);
    }
} else {
    if (validLoginSessionCheck()) {
        $result = getEventsSimpleAuthenticated($_SESSION['idutilizador']);
    } else {
        $result = getEventsPublicSimple();
    }
}

if (!$result) {
    return null;
}
echo $result;
?>