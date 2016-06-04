<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$smarty->display('users/profile.tpl');

?>
