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

// FILE HANDLING
$imagePath = "data/default/foto.png";
$imageFilename = null;
$fotoFile = $_FILES['file'];
$fotoExists = $fotoFile && $fotoFile['tmp_name'] !== "";
if ($fotoExists) {
    if (!isset($fotoFile['error']) || is_array($fotoFile['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($fotoFile['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    if ($fotoFile['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = array_search(
        $finfo->file($fotoFile['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    );

    if ($ext === false) {
        throw new RuntimeException('Invalid file format.');
    } else {
        $imageFilename = sha1_file($fotoFile['tmp_name']) . "." . $ext;
    }
}
// END OF FILE HANDLING

// TODO FIX
//if ($_POST["facebook_photo"]) {
//    $url = $_POST["facebook_photo"];
//    $foto = $BASE_URL . "images/" . $username . '.jpg';
//    copy($url, $BASE_DIR . $foto);
//}

$hashedPassword = create_hash($password);
$idutilizador = insertUser($nome, $username, $hashedPassword, $email, $pais, $imagePath);
if ($idutilizador !== false) {
    if ($fotoExists) {
        $imageDir = "data/users/" . $idutilizador . "/";
        if (!is_dir($BASE_DIR . $imageDir)) {
            mkdir($BASE_DIR . $imageDir, 0711, true);
        }
        $imagePath = $imageDir . $imageFilename;
        if (updateUserPhoto($idutilizador, $imagePath) === false) {
            throw new RuntimeException('Failed to insert image path on database.');
        };
        if (!move_uploaded_file($fotoFile['tmp_name'], $BASE_DIR . $imagePath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    }
} else {
    $_SESSION['error_messages'][] = 'Failed to create Utilizador';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (login($username, $password)) {
    $_SESSION['success_messages'][] = 'Login successful';
    header('Location: ' . $BASE_URL . "pages/event/explore_events.php");
    exit();
}

$_SESSION['error_messages'][] = 'Login failed';
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
