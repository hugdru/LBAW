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
                    <a href="<?php echo $root; ?>/create/">Create</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Guest <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Login</a></li>                            
                        <li><a href="#">Sign up</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>


