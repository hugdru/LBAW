<?php
  include_once('../../config/init.php');
  session_start();
  unset($_SESSION["username"]);
  unset($_SESSION["password"]);
  session_destroy();
  
  header('Location: ' . $BASE_URL);
?>
