<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    session_save_path("../../_system/");


$id = "register"; //This page's identifier

$smarty->assign('id', $id);


    $smarty->assign('action', $BASE_URL . "action/users/register.php");
    $smarty->display('users/register.tpl');
?>
