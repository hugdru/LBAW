<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfLoggedIn($BASE_URL . "pages/event/explore_events.php");

$smarty->assign('currentPage', "login");
$smarty->assign('action', $BASE_URL . "action/users/login.php");
$smarty->assign('actionVars', array("username" => "username", "password" => "password"));

$smarty->display('users/login.tpl');
?>


