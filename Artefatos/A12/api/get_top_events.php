<?php
include_once('../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'functions/users.php');

if (validLoginSessionCheck()) {
    echo getTopEventsAuthenticated($_SESSION['idutilizador']);
} else {
    echo getTopEventsPublic();
}

?>