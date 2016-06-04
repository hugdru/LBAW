<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/users.php');

// TODO Get the event info and then assign it to a smarty variable also support paging
$smarty->assign('currentPage', "view_event");
$smarty->display('event/view_event.tpl');
?>
