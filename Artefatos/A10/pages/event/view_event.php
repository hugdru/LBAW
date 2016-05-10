<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/user.php');

$id = "event"; //This page's identifier
$smarty->assign('id', $id);
    $smarty->display('event/createvent.tpl');

    $title = "Event"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
