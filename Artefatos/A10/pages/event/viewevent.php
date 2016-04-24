<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $smarty->display('event/createvent.tpl');
    $smarty->assign('event', $id);

    $id = "event"; //This page's identifier
    $title = "Event"; //Page title extension
    $root = "..";  //Root location relative to this page
?>