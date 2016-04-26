<nav id="menu" class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
             </button>
            
            <a class="navbar-brand" href="{$BASE_URL}index.php">EventBook</a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav">
                <li {if $id=="myevents" } class="active" {/if}>
                    <a href="{$BASE_URL}pages/event/myevents.php">My Events</a>
                </li>
                <li {if $id=="explore"} class="active" {/if}>
                    <a href="{$BASE_URL}pages/event/explorevent.php">Explore</a>
                </li>
                <li {if $id=="create"} class="active" {/if} >
                    <a href="{$BASE_URL}pages/event/createvent.php">Create</a>
                </li>        
            </ul>

            
            
            <ul class="nav navbar-nav navbar-right">
                    {if !isset($USERNAME)}
                        {if $id=="register"} <li class="active">
                        {else} <li><a href='{$BASE_URL}pages/users/register.php'>Register</a></li>
                        {/if}

                        {if $id=="login"} <li class="active">
                        {else} <li><a href='{$BASE_URL}pages/users/login.php'>Login</a></li>
                        {/if}
                    {else}
                        <li class='dropdown'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                                <img class='img-circle avatar-nav' src='{$BASE_URL}images/avatar_default.png'>{$USERNAME}<span class='caret'></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a href='{$BASE_URL}pages/users/profile.php'>Profile</a></li>
                                <li><a href='{$BASE_URL}pages/users/settings.php'>Settings</a></li>
                                <li role='separator' class='divider'></li>
                                <li><a href='{$BASE_URL}actions/users/logout.php'>Logout</a></li>
                            </ul>
                        </li>
                    {/if}
                
            </ul>
        </div>

    </div>
</nav>


