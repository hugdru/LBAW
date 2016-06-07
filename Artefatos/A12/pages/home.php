<?php
include_once('../config/init.php');
require_once($BASE_DIR . 'functions/thirdParty/passwordHash.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfLoggedIn($BASE_URL . "pages/event/explore_events.php");

$smarty->assign('currentPage', "home");
$smarty->display('home.tpl');
?>
