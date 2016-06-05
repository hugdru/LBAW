<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/admin.php');

//Changes session location to the admin one.
session_save_path("../../_system_admin/");
session_start();

$admin_username = $_SESSION["admin_username"];
$admin_password = $_SESSION["admin_password"];
$auth = authenticate($admin_username, $admin_password);

if ($auth == true) {
    //Login was already done, move to controlpanel
    header("location: control_panel.php");
    exit;
} else if ($auth == false && $_SESSION["alert"]) {
    $smarty->assign('alert', true);
    $smarty->assign('alert_message', $_SESSION["alert"]);
    session_destroy();
}
$smarty->assign('action', $BASE_URL . 'action/admin/login.php');
$smarty->display('admin/login.tpl');
?>
