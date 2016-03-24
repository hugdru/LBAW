<?php
    $id = "about"; //This page's identifier
    $title = "About"; //Page title extension
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
        
        <h1>About</h1>
        <p>Eventbook is a work in progress LBAW Class Project at <a href="https://sigarra.up.pt/feup/pt/web_page.inicial">FEUP</a></p>
        <p>This website makes use of the <a href="http://getbootstrap.com/">Bootstrap Framework</a> and <a href="http://getbootstrap.com/components/#glyphicons">Glyphicons</a></p>
        
        <h1>Work by</h1>
        <p>Diogo Carvalho, up201304573@fe.up.pt</p> 
        <p>Diogo Pereira, up201305602@fe.up.pt</p>
        <p>Hugo Drumond, hugo.drumond@fe.up.pt</p>
        <p>Pedro Albano, ei11016@fe.up.pt</p>
        
        
        
        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>

