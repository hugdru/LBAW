<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/users.php');

if ($_GET["username"]) {
    $username = $_GET["username"];

    if (usernameRegistered($username)) {
        echo '{"exists": true}';
    } else {
        echo '{"exists": false}';
    }
}
?>
