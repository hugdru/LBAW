<?php

function secure_session_start($cookie_path)
{
    $secure = true;
    $httpOnly = true;

    // Avoids session fixation attacks by preventing passing session ids in urls
    if (ini_set('session.use_only_cookies', 1) === false) {
        echo "Can't use only cookies";
        exit();
    }

    $sessionHash = 'whirlpool';

    if (in_array($sessionHash, hash_algos())) {
        ini_set('session.hash_function', $sessionHash);
    }

    ini_set('session.hash_bits_per_character', 5);

    $cookieParams = session_get_cookie_params();
    if (!isset($cookie_path)) {
        $cookie_path = $cookieParams["path"];
    }

    session_set_cookie_params(
        $cookieParams["lifetime"], $cookie_path,
        $cookieParams["domain"], $httpOnly, $secure
    );

    session_start();
    if (!isset($_SESSION['csrf_token'])) {
        session_regenerate_id(true);
        $_SESSION['csrf_token'] = hash("sha512", mt_rand(0, mt_getrandmax()));
    }
}
?>
