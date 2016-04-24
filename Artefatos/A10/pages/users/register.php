<?php
  include_once('../../config/init.php');
  include_once($BASE_DIR .'database/users.php');

//Yet another Gnomo.fe.up.pt fix
//Fixes session default location being unaccessable.
session_save_path("../../_system/");


session_start();

  $smarty->display('users/register.tpl');
  $smarty->assign('register', $id);

  $id = "register"; //This page's identifier
  $title = "Register Account"; //Page title extension
  $root = "..";  //Root location relative to this page

  //Yet another Gnomo.fe.up.pt fix
  //Fixes session default location being unaccessable.
  /*session_save_path("$root/_system/");


  session_start();

  if(!isset($_SESSION["online"])){
	$_SESSION["online"]=false;
  }*/
?>
