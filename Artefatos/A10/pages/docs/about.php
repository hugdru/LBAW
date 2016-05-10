<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

$id = "about"; //This page's identifier
$smarty->assign('id', $id);

    $smarty->display('docs/about.tpl');

    /*$title = "About"; //Page title extension
    $root = "..";  //Root location relative to this page*/
?>



