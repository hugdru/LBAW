<?php
include_once('../../config/init.php');
include_once($BASE_DIR . "database/users.php");

$smarty->assign('currentPage', "register");
$smarty->assign('action', $BASE_URL . "action/users/register.php");
$smarty->assign('actionVars', array(
        "username" => "username",
        "name" => "nome",
        "password" => "password",
        "passwordRepeat" => "repeat_password",
        "email" => "email",
        "country" => "pais",
        "file" => "file",
        "datacriacao" => "datacriacao"
    )
);

//Import Data from Facebook
require_once $BASE_DIR . "/lib/facebook-sdk/autoload.php";

// Whatever you do, don't show these to anyone
$fb = new Facebook\Facebook([
    'app_id' => $FACEBOOK_APP_ID,
    'app_secret' => $FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl("https://gnomo.fe.up.pt" . $BASE_URL . 'action/facebook/callback.php', $permissions);

$fblink = htmlspecialchars($loginUrl);
//End of Facebook Segment

$smarty->assign('fblink', $fblink);

$countryList = getCountryList();

$smarty->assign('countryList', $countryList);

if ($_GET["name"] && $_GET["email"] && $_GET["id"]) {
    $smarty->assign('name', $_GET["name"]);
    $smarty->assign('email', $_GET["email"]);

    $photo = "http://graph.facebook.com/" . $_GET["id"] . "/picture?type=large";
    $smarty->assign('photo', $photo);
}

$smarty->display('users/register.tpl');

?>
