<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $smarty->display('event/myevents.tpl');

    $smarty->assign('myevents', $id);

    $id = "myevents"; //This page's identifier
    $title = "My Events"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
