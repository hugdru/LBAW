<?php
    $id = "home"; //This page's identifier
    $title = "Home"; //Page title extension
    $root = ".";  //Root location relative to this page
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require "$root/res/php/head.php"; ?>
        <link rel="stylesheet" href="res/css/home.css">
    </head>
    <body>
        <?php require "$root/res/php/navbar.php"; ?>
        <?php require "$root/res/php/content-top.php"; ?>        
        <!-- Content Start -->
        
        <h1>Welcome</h1>
        <p>Eventbook allows you to create and share events for any occasion!</p>
        <p>Creating an account is easy! And it only takes a minute or two.</p>
        
        <p id="home-loginbox">
            <a href="account/register.php"><button class="btn btn-default">Register</button></a>
            <a href="account/login.php"><button class="btn btn-primary">Login</button></a>
            <br>
            <small>You can also start to explore public events by <a href="explore/">clicking here</a></small>.
        </p>
        
        
        
        
        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>
