<?php
include_once('../config/init.php');
require_once($BASE_DIR . 'functions/thirdParty/passwordHash.php');

$smarty->assign('currentPage', "home");
$smarty->display('home.tpl');
?>
