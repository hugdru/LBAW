<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'database/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['idSeguidor'], $_POST['idSeguido'])) {
    $_SESSION['error_messages'][] = 'Parameters missing. Something went wrong.';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$idseguidor = $_POST['idSeguidor'];
$idseguido = $_POST['idSeguido'];

if(followUser($idseguidor, $idseguido)){
    $_SESSION['success_messages'][] = 'You are now following this user.';
    header('Location: ' . $BASE_URL . "pages/users/profile.php?id=" . $ideseguido);
    exit();
}
else{
    $_SESSION['error_messages'][] = 'Something went wrong.';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

?>