<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    session_save_path("../../_system/");

    session_start();

    $smarty->assign('action', $BASE_URL . "actions/users/register.php");
    $smarty->display('users/register.tpl');
?>
