<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'database/users.php');

$text = $_GET['text'];

$result = null;
if ($text) {
    $result = getEvents($_GET['text']);
} else {
    $result = getEventsSimple();
}

if (!$result) {
    return null;
}
echo $result;
?>