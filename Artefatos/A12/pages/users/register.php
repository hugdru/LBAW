<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');

    session_save_path("../../_system/");

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
    $loginUrl = $helper->getLoginUrl("http://gnomo.fe.up.pt" . $BASE_URL . 'action/facebook/callback.php', $permissions);

    $fblink = htmlspecialchars($loginUrl);
    //End of Facebook Segment
    
    
    $id = "register"; //This page's identifier

    $smarty->assign('id', $id);
    $smarty->assign('fblink', $fblink);

    $list = getCountryList();
    
    $smarty->assign('list', $list);
    
    if($_GET["name"] && $_GET["email"] && $_GET["id"]){
        $smarty->assign('name', $_GET["name"]);
        $smarty->assign('email', $_GET["email"]);
        
        $photo = "http://graph.facebook.com/" . $_GET["id"] . "/picture?type=large";
        $smarty->assign('photo', $photo);
    }
    
    
    $smarty->assign('action', $BASE_URL . "action/users/register.php");
    $smarty->display('users/register.tpl');
?>
