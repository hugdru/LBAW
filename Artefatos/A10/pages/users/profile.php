<?php

    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');
//Yet another Gnomo.fe.up.pt fix
//Fixes session default location being unaccessable.
session_save_path("../../_system/");


session_start();

$id = "profile"; //This page's identifier
$smarty->assign('id', $id);

 $username = $_POST['username'];
  $password = $_POST['password'];
  
  if (isLoginCorrect($username, $password)) {
    $_SESSION['username'] = $username;
    $_SESSION['success_messages'][] = 'Login successful';  
  } else {
    $_SESSION['error_messages'][] = 'Login failed';  
  }


    $smarty->display('users/profile.tpl');

    $title = "Profile"; //Page title extension
    $root = "..";  //Root location relative to this page
?>
