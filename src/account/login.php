<?php
    $id = "login"; //This page's identifier
    $title = "Login"; //Page title extension
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
            <h1>Login</h1>           

            <form role="form">
                <div class="form-group">
                    <label for="usr">Username</label>
                    <input type="text" class="form-control" id="usr">
                </div>
                <div class="form-group">
                    <label for="pwd">Password</label>
                    <input type="password" class="form-control" id="pwd">
                </div>
                <div class="checkbox">
                    <label><input type="checkbox"> Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>

        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>


