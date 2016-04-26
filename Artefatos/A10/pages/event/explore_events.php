<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $id = "explore"; //This page's identifier
    $smarty->assign('id', $id);
    $smarty->display('event/explorevent.tpl');



    $title = "Explore Events"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
