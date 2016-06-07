<?php

require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_POST['csrf'])) {
    $_SESSION['error_messages'][] = 'An unauthorized request was made';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (!isset($_POST['idutilizador'], $_POST['password'], $_POST['newPassword'], $_POST['newRepeatPassword'], $_POST["newEmail"], $_POST["newDescription"], $_FILES["newPhoto"])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

$redirect = 'Location: ' . $BASE_URL . "pages/users/settings.php";

$updateEmail = false;
$updatePhoto = false;
$updatePassword = false;
$updateDescription = false;


if (!empty($_POST['idutilizador']) && !empty($_POST['password']) && !empty($_POST['newPassword']) && !empty($_POST['newRepeatPassword'])) {
    $updatePassword = true;
    $idutilizador = $_POST['idutilizador'];
    $password = $_POST['password'];
    $newPassword = $_POST['newPassword'];
    $newRepeatPassword = $_POST['newRepeatPassword'];
}
if (isset($_POST['newEmail'])) {
    $updateEmail = true;
    $newEmail = $_POST['newEmail'];
}
if (isset($_POST['newDescription'])){
    $updateDescription = true;
    $newDescription = $_POST['newDescription'];
}
if (!empty($_FILES['newPhoto']['name'])){
    $updatePhoto = true;
    $newPhoto = $_FILES['newPhoto'];
}
/*
var_dump($updatePhoto);
exit;*/

if ($updatePassword){
    if ($idutilizador != $_SESSION['idutilizador']) {
        $_SESSION['error_messages'][] = 'ERROR - Password update: You can only change your password';
        header($redirect);
        exit();
    }

    if ($newPassword != $newRepeatPassword) {
        $_SESSION['error_messages'][] = 'ERROR - Password update: New passwords mismatch';
        header($redirect);
        exit();
    }

    $newPasswordLength = strlen($newPassword);
    if ($newPasswordLength < 8 || $newPasswordLength > 100) {
        $_SESSION['error_messages'][] = 'ERROR - Password update: New password should have between 8 and 100 characters';
        header($redirect);
        exit();
    }

    if (!validLoginDatabaseCheck($idutilizador, $password)) {
        $_SESSION['error_messages'][] = 'ERROR - Password update: Original password is incorrect';
        header($redirect);
        exit();
    }

    $hashedNewPassword = create_hash($newPassword);

    if (updatePassword($idutilizador, $hashedNewPassword) === false) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    $_SESSION['success_messages'][] = 'Password updated successfully';

}

if ($updatePhoto){
    // FILE HANDLING

    $imageFilename = null;
    $fotoFile = $newPhoto;
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

        if ($fotoFile['size'] > 10000000) {
            $_SESSION['error_messages'][] = 'ERROR - Photo update: Picture size not valid. Upload files only with size under 10Mb';
            header($redirect);
            exit();
            //throw new RuntimeException('Exceeded filesize limit.');
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
    } else {
        $_SESSION['error_messages'][] = 'ERROR - Photo update: You must choose a photo';
        header($redirect);
        exit();
    }
// END OF FILE HANDLING



    $imageDir = "data/users/" . $_SESSION["idutilizador"] . "/";
    if (!is_dir($BASE_DIR . $imageDir)) {
        mkdir($BASE_DIR . $imageDir, 0711, true);
    }
    $imagePath = $imageDir . $imageFilename;


    if (updatePhoto($_SESSION["idutilizador"], $imagePath) === false) {
        throw new RuntimeException('Failed to insert image path on database.');
    }
    if (!move_uploaded_file($fotoFile['tmp_name'], $BASE_DIR . $imagePath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    if (file_exists($BASE_DIR . $_SESSION['foto'])) {
        $deleted = unlink($BASE_DIR . $_SESSION['foto']);
        if (!$deleted) {
            throw new RuntimeException("Could not delete filesystem");
        }
    }
    $_SESSION['foto'] = $imagePath;

    $_SESSION['success_messages'][] = 'Photo updated successfully';
}

if ($updateDescription){
    if (updateDescription($_SESSION["idutilizador"], $newDescription))
        $_SESSION["descricao"] = $newDescription;

    $_SESSION['success_messages'][] = 'Description updated successfully';
}

if ($updateEmail){
    if (updateEmail($_SESSION["idutilizador"], $newEmail))
        $_SESSION["email"] = $newEmail;

    $_SESSION['success_messages'][] = 'Email updated successfully';
}

header('Location: ' . $BASE_URL . "pages/users/profile.php");
exit();

?>