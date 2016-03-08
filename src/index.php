<?php
    $id = "home"; //This page's identifier
    $title = "Home"; //Page title extension
    $root = ".";  //Root location relative to this page
?>

<!DOCTYPE html>
<html>
    <?php require "$root/res/php/head.php"; ?>
    <body>
        <?php require "$root/res/php/navbar.php"; ?>
        <?php require "$root/res/php/content-top.php"; ?>        
        <!-- Content Start -->
        
        <h1>EventBook</h1>
        <p>Press <kbd>ctrl + w</kbd> to access your account.</p>
        
        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
        <?php require "$root/res/php/footer.php"; ?>
    </body>
</html>
