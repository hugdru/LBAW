<?php

require_once($BASE_DIR . 'database/users.php');
require_once($BASE_DIR . 'functions/thirdParty/passwordHash.php');


function redirectIfNotLoggedIn($page)
{
    if (!isset($_SESSION["username"])) {
        header("Location: " . $page);
        exit();
    }
}

function redirectIfLoggedIn($page)
{
    if (isset($_SESSION["username"])) {
        header("Location: " . $page);
        exit();
    }
}

function login($username, $password)
{
    $userData = getUserByUsername($username);
    if ($userData) {
        if (validate_password($password, $userData['password'])) {
            foreach ($userData as $field => $value) {
                if ($field === 'password') {
                    continue;
                }
                if ($field === "idpais"){
                    $_SESSION["idpais"] = $value;
                    $_SESSION["pais"] = getCountryById($value);
                }
                else {
                    $_SESSION[$field] = $value;
                }
            }
            session_regenerate_id(true);
            return true;
        }
        return false;
    }
    return false;
}

function validLoginSessionCheck()
{
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
}

function validLoginDatabaseCheck($id, $password)
{
    $userData = getUserById($id);
    if ($userData) {
        if (validate_password($password, $userData['password'])) {
            return true;
        }
        return false;
    }
    return false;
}

?>
