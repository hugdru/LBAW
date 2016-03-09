<?php
    $id = "create"; //This page's identifier
    $title = "Create Event"; //Page title extension
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
        
        <h1>Create Event</h1>           

        <form role="form">
            <div class="form-group">
                <label for="evt">Event Title</label>
                <input type="text" class="form-control" id="evt">
            </div>

            
            <div class="form-group">
                <label for="eml">Cover Picture</label>
                <input type="file" class="form-control-static">
            </div>
            
            <div class="form-group">
                <label for="eml">Time and Date</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="tmd">
                    <div class="input-group-btn">
                       <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-time"></i>
                       </button>
                    </div>
                </div>
            </div>
                

            <div class="form-group">
                <label for="dsc">Description</label>
                <textarea style="min-height: 200px; resize: none;" class="form-control" id="dsc"></textarea>
            </div>

            <div class="form-group">
                <label form="lct">Location</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="lct">
                    <div class="input-group-btn">
                       <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-globe"></i>
                       </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
        
        <p/>

        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>



