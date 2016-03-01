<?
function add_message($status, $message){
  if(isset($_SESSION[$status]))
    $_SESSION[$status][count($_SESSION[$status])] = $message;
  else
    $_SESSION[$status][0] = $message;
}

// Error message
function add_error_message($message){
  add_message('error_message', $message);
}

// Success message
function add_success_message($message){
  add_message('success_message', $message);
}
?>
