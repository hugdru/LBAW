<?php
    session_start();
    
    if($_SESSION["online"]==false){
        $_SESSION["online"]=true;
    }else{
        $_SESSION["online"]=false;
    }

    header('Location: ..');
?>

