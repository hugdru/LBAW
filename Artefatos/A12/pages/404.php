<?php
include_once('../config/init.php');

$smarty->assign('currentPage', "404");
$smarty->display('404.tpl');
?>