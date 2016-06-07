<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/users.php');

if ($_GET["email"]) {
    $email = $_GET["email"];

    if (emailRegistered($email)) {
        echo '{"exists": true}';
    } else {
        echo '{"exists": false}';
    }
}
?>