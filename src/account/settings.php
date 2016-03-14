<?php
    $id = "settings"; //This page's identifier
    $title = "Settings"; //Page title extension
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
        

        <h1>Settings</h1>           

        <div class="row">
            <div class="col-sm-4">
                <form role="form">
                    <h3>Change Account Picture</h3>
                    <div class="form-group">
                        <label for="eml">Upload Picture</label>
                        <input type="file" class="form-control-static">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
            
            <div class="col-sm-4">
                <form role="form">
                    <h3>Change Email Address</h3>
                    <div class="form-group">
                        <label for="eml">Email Address</label>
                        <input type="email" class="form-control" id="eml" value="someone@somewhere.whom">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
           
            <div class="col-sm-4">
                <form role="form">
                    <h3>Change Password</h3>
                    <div class="form-group">
                        <label for="pwd1">Current Password</label>
                        <input type="password" class="form-control" id="pwd1">
                    </div>

                    <div class="form-group">
                        <label for="pwd2">New Password</label>
                        <input type="password" class="form-control" id="pwd2">
                    </div>

                    <div class="form-group">
                        <label for="pwd3">New Password (Repeat)</label>
                        <input type="password" class="form-control" id="pwd3">
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>


