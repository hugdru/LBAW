<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$smarty->assign('actionUpdatePassword', $BASE_URL . "action/users/updatePassword.php");
$smarty->assign('actionUpdatePasswordVars', array(
        "id" => "id",
        "password" => "password",
        "newPassword" => "newPassword",
        "newRepeatPassword" => "newRepeatPassword"
    )
);
$smarty->assign('passwordReply', $_GET["passwordReply"]);

$smarty->display('users/settings.tpl');
?>




