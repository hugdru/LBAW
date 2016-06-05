<?php
//include_once('https.php');
include_once('secureSession.php');

secure_session_start("/");

error_reporting(E_ERROR | E_WARNING);

$BASE_DIR = '/home/diogo/Desktop/eventook/Artefatos/A12/';
$BASE_URL = '/';

$FACEBOOK_APP_ID = '144439699302501';
$FACEBOOK_APP_SECRET = 'db03211a6c451296935ed61d2de6fd6e';

$conn = new PDO('pgsql:host=localhost;dbname=final', 'admin', 'admin');
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->exec('SET SCHEMA \'final\'');

include_once($BASE_DIR . 'lib/smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = $BASE_DIR . 'templates/';
$smarty->compile_dir = $BASE_DIR . 'templates_c/';

$smarty->assign('BASE_URL', $BASE_URL);
$smarty->assign('ERROR_MESSAGES', $_SESSION['error_messages']);
$smarty->assign('FIELD_ERRORS', $_SESSION['field_errors']);
$smarty->assign('SUCCESS_MESSAGES', $_SESSION['success_messages']);
$smarty->assign('FORM_VALUES', $_SESSION['form_values']);

unset($_SESSION['success_messages']);
unset($_SESSION['error_messages']);
unset($_SESSION['field_errors']);
unset($_SESSION['form_values']);
?>
