<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');
//Yet another Gnomo.fe.up.pt fix
//Fixes session default location being unaccessable.
session_save_path("../../_system/");


session_start();
$id = "settings"; //This page's identifier
$smarty->assign('id', $id);

    $smarty->display('users/settings.tpl');
;


    $title = "Settings"; //Page title extension
    $root = "..";  //Root location relative to this page
?>




