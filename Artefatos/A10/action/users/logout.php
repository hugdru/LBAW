<?php
  include_once('../../config/init.php');
  session_start();
  unset($_SESSION["username"]);
  session_destroy();
  
  header('Location: ' . $BASE_URL);
?>
