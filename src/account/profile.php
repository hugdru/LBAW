<?php
    $id = "profile"; //This page's identifier
    $title = "Profile"; //Page title extension
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
        <div class="row">
            <div id="profile-imgbox" class="col-md-4">
                <img class="img-circle" style="min-width: 100%" src="../res/img/avatar_default.png" />
            </div>
            <div id="profile-detailbox" class="col-md-8">
                <h2>Esteves Prototipo</h2>
                <p id="desc">Hello, i'm a test user for EventBook</p>
                
                <label for="region"> <i class="glyphicon glyphicon-map-marker"></i> Location</label>
                <p id="region">Portugal</p>
                
                <label for="since"> <i class="glyphicon glyphicon-time"></i> Member Since</label>
                <p id="since">January 2016</p>
                
                <label for="since"> <i class="glyphicon glyphicon-stats"></i> Statistics</label>
                <p id="since">Events: Joined 50, Hosted 10</p>
            </div>
        </div>
        
        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>
