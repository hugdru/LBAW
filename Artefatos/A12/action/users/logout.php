<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['csrf'])) {
    $_SESSION['error_messages'][] = 'An unauthorized request was made';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$csrf = $_POST['csrf'];
if ($csrf !== $_SESSION['csrf_token']) {
    $_SESSION['error_messages'][] = 'An unauthorized request was made';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header('Location: ' . $BASE_URL);
exit();
?>
