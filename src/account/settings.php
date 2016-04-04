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
                    <h3>Profile: Picture</h3>
                    <div class="form-group">
                        <input type="file" class="form-control-static">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
            
            <div class="col-sm-4">
                <form role="form">
                    <h3>Profile: Description</h3>
                    <div class="form-group">
                        <textarea style="min-height: 100px; resize: none;" class="form-control" id="dsc">Hello, i'm a test user for EventBook</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>            
            
            <div class="col-sm-4">
                <form role="form">
                    <h3>Profile: Region</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" id="rgn" value="Portugal">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
        
        <div class="row">
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
            
            <div class="col-sm-4">
                <form role="form">
                    <h3>Notification Preferences</h3>
                    <div class="form-group">
                        <label>Send me a Email when</label>
                        <div class="checkbox">
                            <label><input type="checkbox" value="">I'm invited to an Event</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="">An event i follow has updates</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="">EventBook has recommended events for me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

        <!-- Content Finish -->
        <?php require "$root/res/php/content-bottom.php"; ?>
    </body>
</html>


