<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR . "database/admin.php");

    if (!$_POST['idcomentario']) {
        $_SESSION['error_messages'][] = 'Parameter missing: idcomentario';
        $_SESSION['form_values'] = $_POST;
        exit;
    }

    $admin_username = $_SESSION['admin_username'];
    $admin_password = $_SESSION['admin_password'];

    //Guarantee action is being done by an admin
    if(authenticate($admin_username, $admin_password)) {
        removeComment($_POST["idcomentario"]);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>

