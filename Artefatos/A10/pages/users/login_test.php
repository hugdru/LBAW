<?php
    //Yet another Gnomo.fe.up.pt fix
    //Fixes session default location being unaccessable.
    session_save_path("../../_system/");

    session_start();
    
    if($_SESSION["online"]==false){
        $_SESSION["online"]=true;
    }else{
        $_SESSION["online"]=false;
    }

    header('Location: ../../index.php');
?>

