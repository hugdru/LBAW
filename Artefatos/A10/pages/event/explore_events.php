<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/user.php');


    $id = "explore"; //This page's identifier
    $smarty->assign('id', $id);
    $smarty->display('event/explorevents.tpl');



    $title = "Explore Events"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
