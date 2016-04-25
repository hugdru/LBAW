<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR . 'database/admin.php');
    
    //Changes session location to the admin one.
    session_save_path("../../_system_admin/");
    session_start();
    
    //Check if session is valid
    $admin_username = $_SESSION["admin_username"];
    $admin_password = $_SESSION["admin_password"];
    $auth = authenticate($admin_username, $admin_password);
    
    if($auth == false){
        $_SESSION['error_messages'][] = 'Login failed';  
        $_SESSION['alert'] = 'Session data not found or expired.';
        header("location: login.php");
        exit;
    }
    
    $data = getAdminData();
    
    //Set active option/tab
    $option = 1;
    
    if($_GET["option"] == true){
        $option = $_GET["option"];
    }
    
    if($option == 1){
        $list = getUserList();
        $smarty->assign('list', $list);
    }
    
    $smarty->assign('option', $option);
    $smarty->assign('account_username', $data["username"]);
    $smarty->assign('account_email', $data["email"]);   
    $smarty->assign('logout_action', $BASE_URL . 'actions/admin/logout.php');
    $smarty->assign('action_user_remove', $BASE_URL . 'actions/admin/remove_user.php');
    $smarty->display('admin/control_panel.tpl');
?>


