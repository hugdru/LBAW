<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $smarty->display('event/createvent.tpl');

    $smarty->assign('create', $id);

    $id = "create"; //This page's identifier
    $title = "Create Event"; //Page title extension
    $root = "..";  //Root location relative to this page
?>



