<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL);

if (!isset($_FILES['newPhoto'])) {
    $_SESSION['error_messages'][] = 'Parameters Missing';
    $_SESSION['form_values'] = $_POST;
    header('Location: ' . $BASE_URL . "pages/users/settings.php");
    exit;
}

$newPhoto = $_FILES['newPhoto'];

$redirect = 'Location: ' . $BASE_URL . "pages/users/settings.php";

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
header($redirect);
exit();

?>