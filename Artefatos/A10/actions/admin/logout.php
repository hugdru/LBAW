<?php
    include_once('../../config/init.php');
    
    // Activate session and destroy it
    session_start();
    session_destroy();    
    
    header("location: " . $BASE_URL . "pages/admin/login.php");
?>

