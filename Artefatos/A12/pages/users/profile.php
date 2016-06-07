<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'database/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

if($_GET["id"]){
    $user = getUserById($_GET["id"]);
}
else{
    $user = getUserById($_SESSION["idutilizador"]);
}
$user["pais"] = getCountryById($user["idpais"]);
$user["joins"] = getJoinedEventsByUser($user['idutilizador']);
$user["hosts"] = getHostedEventsByUser($user['idutilizador']);

$smarty->assign("user", $user);

$smarty->assign('currentPage', "profile");
$smarty->display('users/profile.tpl');

?>
