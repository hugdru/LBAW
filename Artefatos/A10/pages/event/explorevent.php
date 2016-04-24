<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    $smarty->display('event/explorevent.tpl');
    $smarty->assign('explore', $id);

    $id = "explore"; //This page's identifier
    $title = "Explore Events"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
