<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

if($_GET["id"]){
    $user = getUserById($_GET["id"]);
    
    if($user){
        $user["pais"] = getCountryById($user["idpais"]);
    }
    $smarty->assign("user", $user);
}

$smarty->assign('currentPage', "profile");
$smarty->display('users/profile.tpl');

?>
