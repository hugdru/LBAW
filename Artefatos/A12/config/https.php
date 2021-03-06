<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');

$use_sts = true;

if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=31536000');
} else if ($use_sts) {
    header(
        'Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        true, 301
    );
    exit();
}
?>
