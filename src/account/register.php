<?php
    $id = "register"; //This page's identifier
    $title = "Register Account"; //Page title extension
    $root = "..";  //Root location relative to this page
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
        

        <div class="accountfield">
            <h1>Register Account</h1>           

            <form role="form">
                <div class="form-group">
                    <label for="usr">Username</label>
                    <input type="text" class="form-control" id="usr">
                </div>
                
                <div class="form-group">
                    <label for="eml">Email Address</label>
                    <input type="email" class="form-control" id="eml">
                </div>
                
                <div class="form-group">
                    <label for="pwd1">Password</label>
                    <input type="password" class="form-control" id="pwd1">
                </div>
                
                <div class="form-group">
                    <label for="pwd2">Password (Repeat)</label>
                    <input type="password" class="form-control" id="pwd2">
                </div>
                
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>


