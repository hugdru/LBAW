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

$smarty->assign('actionUpdateDescription', $BASE_URL . "action/users/updateDescription.php");
$smarty->assign('actionUpdateDescriptionVars', array(
        "oldDescription" => "oldDescription",
        "newDescription" => "newDescription"
    )
);

$smarty->assign('actionUpdateEmail', $BASE_URL . "action/users/updateEmail.php");
$smarty->assign('actionUpdateEmailVars', array(
        "newEmail" => "newEmail"
    )
);


$smarty->assign('passwordReply', $_GET["passwordReply"]);
$smarty->assign('pictureReply', $_GET["pictureReply"]);
$smarty->assign('descriptionReply', $_GET["descriptionReply"]);
$smarty->assign('emailReply', $_GET["emailReply"]);

$smarty->display('users/settings.tpl');
?>




