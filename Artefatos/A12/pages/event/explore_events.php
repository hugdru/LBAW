<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/users.php');

$smarty->assign('currentPage', "explore_events");
$smarty->display('event/explore_events.tpl');
?>
