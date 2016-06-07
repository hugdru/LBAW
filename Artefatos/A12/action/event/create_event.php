<?php
require_once('../../config/init.php');
require_once($BASE_DIR . 'functions/users.php');
require_once($BASE_DIR . 'database/event.php');

redirectIfNotLoggedIn('Location: ' . $_SERVER['HTTP_REFERER']);

if (!isset($_POST['titulo'], $_POST['descricao'], $_POST['localizacao'], $_POST['dataInicio'], $_POST['duracao'], $_POST['publico'], $_POST['csrf'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$csrf = $_POST['csrf'];
if ($csrf !== $_SESSION['csrf_token']) {
    $_SESSION['error_messages'][] = 'An unauthorized request was made';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$titulo = $_POST['titulo'];
$tituloLength = strlen($titulo);
if ($tituloLength < 4 || $tituloLength > 100) {
    $_SESSION['error_messages'][] = 'Title must be between 4 and 100 characters';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$descricao = $_POST['descricao'];
$localizacao = $_POST['localizacao'];
$dataInicio = $_POST['dataInicio'];

$duracao = intval($_POST['duracao']);
if ($duracao <= 0) {
    $_SESSION['error_messages'][] = 'Duration must be greater than 0';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$publico = $_POST['publico'];
if ($publico !== "true" && $publico !== "false") {
    $_SESSION['error_messages'][] = 'Event visibility attribute must be true or false';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// FILE HANDLING
$imagePath = "data/default/capa.jpg";
$imageFilename = null;
$capaFile = $_FILES['capa'];
$capaExists = $capaFile && $capaFile['tmp_name'] !== "";
if ($capaExists) {
    if (!isset($capaFile['error']) || is_array($capaFile['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($capaFile['error']) {
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

    if ($capaFile['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = array_search(
        $finfo->file($capaFile['tmp_name']),
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
        $imageFilename = sha1_file($capaFile['tmp_name']) . "." . $ext;
    }
}

$idEvento = insertEvent($titulo, $imagePath, $descricao, $localizacao, $dataInicio, $duracao, $publico, $_SESSION['idutilizador']);

if ($idEvento !== false) {
    if ($capaExists) {
        $imageDir = "data/event/" . $idEvento . "/";
        if (!is_dir($BASE_DIR . $imageDir)) {
            mkdir($BASE_DIR . $imageDir, 0711, true);
        }
        $imagePath = $imageDir . $imageFilename;
        if (updateEventPhoto($idEvento, $imagePath) === false) {
            throw new RuntimeException('Failed to insert image path on database.');
        };
        if (!move_uploaded_file($capaFile['tmp_name'], $BASE_DIR . $imagePath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    }
    $_SESSION['success_messages'][] = 'Your event was created successfully';
    header('Location: ' . $BASE_URL . "pages/event/view_event.php?id=" . $idEvento);
    exit();
} else {
    $_SESSION['error_messages'][] = 'An unexpected error has occurred. Failed to create event';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>