<?php

include_once('../../config/init.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$smarty->assign('currentPage', "settings");
$smarty->assign('actionUpdatePasswordVars',
    array(
        "idutilizador" => "idutilizador",
        "password" => "password",
        "newPassword" => "newPassword",
        "newRepeatPassword" => "newRepeatPassword",
        "csrf" => "csrf"
    )
);

$smarty->assign('actionUpdatePhotoVars', array(
        "idutilizador" => "idutilizador",
        "photo" => "photo",
        "newPhoto" => "newPhoto"
    )
);

$smarty->assign('actionUpdateDescriptionVars', array(
        "oldDescription" => "oldDescription",
        "newDescription" => "newDescription"
    )
);

$smarty->assign('actionUpdateEmailVars', array(
        "newEmail" => "newEmail"
    )
);

$smarty->assign('countryReply', $_GET["countryReply"]);
$smarty->assign('passwordReply', $_GET["passwordReply"]);
$smarty->assign('pictureReply', $_GET["pictureReply"]);
$smarty->assign('descriptionReply', $_GET["descriptionReply"]);
$smarty->assign('emailReply', $_GET["emailReply"]);

$smarty->display('users/settings.tpl');
?>




