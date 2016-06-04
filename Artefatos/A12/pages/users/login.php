<?php
include_once('../../config/init.php');

$smarty->assign('currentPage', "login");
$smarty->assign('action', $BASE_URL . "action/users/login.php");
$smarty->assign('actionVars', array("username" => "username", "password" => "password"));

$smarty->display('users/login.tpl');
?>


