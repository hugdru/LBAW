<?php
include_once('../../config/init.php');

$smarty->assign('currentPage', "top_events");
$smarty->display('event/top_events.tpl');
?>