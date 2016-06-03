<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR . "database/admin.php");

    if (! ($_POST['cad_username'] && $_POST['cad_email'] && $_POST['cad_password'] && $_POST['cad_password_repeat'])) {
        $_SESSION['error_messages'][] = 
                '<strong>Create Admin Account Failed:</strong> One or More Parameters Missing';
        $_SESSION['form_values'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    
    $admin_username = $_SESSION['admin_username'];
    $admin_password = $_SESSION['admin_password'];

    //Guarantee action is being done by an admin
    if(authenticate($admin_username, $admin_password)) {
        createAdmin($_POST["cad_username"], $_POST['cad_email'],$_POST['cad_password'], $_POST['cad_password_repeat']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>

