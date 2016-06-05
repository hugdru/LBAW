<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$smarty->assign('currentPage', "settings");
$smarty->assign('actionUpdatePassword', $BASE_URL . "action/users/updatePassword.php");
$smarty->assign('actionUpdatePasswordVars', array(
        "idutilizador" => "idutilizador",
        "password" => "password",
        "newPassword" => "newPassword",
        "newRepeatPassword" => "newRepeatPassword"
    )
);

$smarty->assign('actionUpdatePhoto', $BASE_URL . "action/users/updatePhoto.php");

$smarty->assign('actionUpdatePhotoVars', array(
    "idutilizador" => "idutilizador",
        "photo" => "photo",
        "newPhoto" => "newPhoto"
)
);

$smarty->assign('passwordReply', $_GET["passwordReply"]);

$smarty->display('users/settings.tpl');
?>




