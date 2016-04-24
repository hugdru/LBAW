<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');
//Yet another Gnomo.fe.up.pt fix
//Fixes session default location being unaccessable.
session_save_path("../../_system/");


session_start();

    $smarty->display('users/settings.tpl');

    $smarty->assign('settings', $id);

    $id = "settings"; //This page's identifier
    $title = "Settings"; //Page title extension
    $root = "..";  //Root location relative to this page
?>




