<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');
include_once($BASE_DIR . 'database/event.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$joins = getJoinedEventsByUser($_SESSION["idutilizador"]);
$hosts = getHostedEventsByUser($_SESSION["idutilizador"]);

$smarty->assign("joins", $joins);
$smarty->assign("hosts", $hosts);

$smarty->assign('currentPage', "profile");
$smarty->display('users/profile.tpl');

?>
