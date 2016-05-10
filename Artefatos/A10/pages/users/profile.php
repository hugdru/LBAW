<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/user.php');
    include_once($BASE_DIR .'functions/user.php');
//Yet another Gnomo.fe.up.pt fix
//Fixes session default location being unaccessable.
session_save_path("../../_system/");

isLoggedIn($BASE_URL . "pages/users/login.php");

$id = "profile"; //This page's identifier
$smarty->assign('id', $id);


    $smarty->display('users/profile.tpl');

    $title = "Profile"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
