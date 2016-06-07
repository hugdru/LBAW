<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'database/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");



if($_GET["id"]){
    $user = getUserById($_GET["id"]);
    $idOutro = $user;
    $other_user= true;
}
else{
    $user = getUserById($_SESSION["idutilizador"]);
    $other_user= false;
}

$follows = false;
if($other_user){
    $follows = isFollowing($_SESSION["idutilizador"], $user);
}
$user["pais"] = getCountryById($user["idpais"]);
$user["joins"] = getJoinedEventsByUser($user['idutilizador']);
$user["hosts"] = getHostedEventsByUser($user['idutilizador']);

$smarty->assign("ownId", $_SESSION["idutilizador"]);
$smarty->assign("follows", $follows);
$smarty->assign("other", $other_user);
$smarty->assign("user", $user);
$smarty->assign('actionFollowUser', array(
        "idSeguidor" => "idSeguidor",
        "idSeguido" => "idSeguido"
    )
);

$smarty->assign('currentPage', "profile");
$smarty->display('users/profile.tpl');

?>
