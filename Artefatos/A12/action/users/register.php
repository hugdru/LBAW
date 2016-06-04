<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'functions/thirdParty/passwordHash.php');

redirectIfLoggedIn($BASE_URL);

if (!isset($_POST['username'], $_POST['nome'], $_POST['password'], $_POST['repeat_password'], $_POST['email'], $_POST["pais"])) {
    $_SESSION['error_messages'][] = 'All fields are mandatory';
    $_SESSION['form_values'] = $_POST;
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

$nome = $_POST['nome'];

$username = $_POST['username'];
if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\.\-_]{2,15}$/', $username)) {
    $_SESSION['error_messages'][] = 'Username can only have between 3 and 16 characters.';
    $_SESSION['form_values'] = $_POST;
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

$password = $_POST['password'];
$passwordLength = strlen($password);
if ($passwordLength > 100) {
    $_SESSION['error_messages'][] = 'Password is too long';
    $_SESSION['form_values'] = $_POST;
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
} else if ($passwordLength < 8) {
    $_SESSION['error_messages'][] = 'Password has to have at least 8 characters';
    $_SESSION['form_values'] = $_POST;
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

$repeatedPassword = $_POST['repeat_password'];

$email = $_POST["email"];
if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@.]+$/', $email)) {
    $_SESSION['error_messages'][] = 'Please type a valid email';
    $_SESSION['form_values'] = $_POST;
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}
$pais = $_POST["pais"];

if ($password != $repeatedPassword) {
    $_SESSION['error_messages'][] = 'Password and Repeated Password mismatch';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (checkIfUsernameExists($username)) {
    $_SESSION['error_messages'][] = 'Username already in use';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (checkIfEmailExists($email)) {
    $_SESSION['error_messages'][] = 'Email already in use';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$foto = "images/avatar_default.png";
if ($_FILES['file']) {
    $postFoto = $_FILES['file'];

    if (getimagesize($postFoto["tmp_name"]) !== false) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $foto = "images/" . $username . '.' . $ext;

        move_uploaded_file($_FILES["file"]["tmp_name"], $BASE_DIR . $foto);
    }
}

if($_POST["facebook_photo"]){
    $url = $_POST["facebook_photo"];
    $foto = "images/" . $username . '.jpg';
   
    copy($url, $BASE_DIR . $foto);
}

$hashedPassword = create_hash($password);
insertUser($nome, $username, $hashedPassword, $email, $pais, $foto);

if (login($username, $password)) {
    $_SESSION['success_messages'][] = 'Login successful';
    header('Location: ' . $BASE_URL . "pages/event/explore_events.php");
    exit();
}

$_SESSION['error_messages'][] = 'Login failed';
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
