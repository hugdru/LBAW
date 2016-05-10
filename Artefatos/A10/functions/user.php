<?php


    function isLoggedIn($page){
        session_start();

        if (isset($_SESSION["username"])){
            header("Location: " . $page);
            exit();
        }
    }
?>