<?php
  include_once('../../config/init.php');
  include_once($BASE_DIR .'database/user.php');  

  if (!$_POST['updt_username'] || !$_POST['updt_original_password'] 
          || !$_POST['updt_new_password'] || !$_POST['updt_confirm_new_password']) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
  }

  $updt_username = $_POST['updt_username'];
  $updt_password_original = $_POST['updt_original_password'];
  $updt_password_new = $_POST['updt_new_password'];
  $updt_password_confirm_new = $_POST['updt_confirm_new_password'];
  
  $result = updatePassword($updt_username, $updt_password_original, $updt_password_new, $updt_password_confirm_new);

  header('Location: ' . $BASE_URL . "pages/users/settings.php" . "?pwr=" . $result);
?>
