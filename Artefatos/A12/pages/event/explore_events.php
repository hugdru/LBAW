<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/users.php');

// TODO Get the top events and then assign them to a smarty variable also support paging
$smarty->assign('currentPage', "explore_events");
$smarty->display('event/explore_events.tpl');
?>
