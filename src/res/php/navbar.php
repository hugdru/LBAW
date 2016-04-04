<nav id="menu" class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
             </button>
            
            <a class="navbar-brand" href="<?php echo $root; ?>/">EventBook</a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav">
                <li <?php if($id=="myevents"){ echo 'class="active"'; }?>>
                    <a href="<?php echo $root; ?>/myevents/">My Events</a>
                </li>
                <li <?php if($id=="explore"){ echo 'class="active"'; }?>>
                    <a href="<?php echo $root; ?>/explore/">Explore</a>
                </li>
                <li <?php if($id=="create"){ echo 'class="active"'; }?>>
                    <a href="<?php echo $root; ?>/create/">
                        Create
                    </a>
                </li>        
            </ul>

            
            
            <ul class="nav navbar-nav navbar-right">
                
                <?php
                    if($_SESSION["online"]==false){
                        echo ($id=="register" ? "<li class='active'>" : "<li>");
                        echo "  <a href='$root/account/register.php'>Register</a>";
                        echo "</li>";
                        echo ($id=="login" ? "<li class='active'>" : "<li>");
                        echo "  <a href='$root/account/login.php'>Login</a>";
                        echo "</li>"; 
                    }else{
                        echo "
                        <li class='dropdown'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                                <img class='img-circle avatar-nav' src='$root/res/img/avatar_default.png'> Esteves Prototipo <span class='caret'></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a href='$root/account/profile.php'>Profile</a></li>
                                <li><a href='$root/account/settings.php'>Settings</a></li>
                                <li role='separator' class='divider'></li>
                                <li><a href='$root/account/login_test.php'>Logout</a></li>
                            </ul>
                        </li>
                        ";
                    }
                ?>     
                
            </ul>
        </div>

    </div>
</nav>


