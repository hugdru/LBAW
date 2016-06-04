<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/users.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

// TODO Get the user events and then assign them to a smarty variable also support paging
$smarty->assign('currentPage', "my_events");
$smarty->display('event/my_events.tpl');
?>
