<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $id = "create"; //This page's identifier
    $smarty->assign('id', $id);

    $smarty->display('event/createvent.tpl');


    $title = "Create Event"; //Page title extension
    $root = "..";  //Root location relative to this page
?>



