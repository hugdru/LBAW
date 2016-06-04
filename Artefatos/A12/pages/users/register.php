<?php
include_once('../../config/init.php');

$smarty->assign('currentPage', "register");
$smarty->assign('action', $BASE_URL . "action/users/register.php");
$smarty->assign('actionVars', array(
        "username" => "username",
        "name" => "nome",
        "password" => "password",
        "passwordRepeat" => "repeat_password",
        "email" => "email",
        "country" => "pais",
        "file" => "file"
    )
);

$smarty->display('users/register.tpl');
?>
