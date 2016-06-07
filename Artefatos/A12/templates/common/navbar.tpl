<nav id="menu" class="navbar navbar-inverse navbar-fixed-top">
    {if isset($smarty.session.username)}
    <script type="text/javascript">
        function logout(){
            var inputCsrf = document.createElement("input");
            inputCsrf.setAttribute("type", "hidden");
            inputCsrf.setAttribute("name", "csrf");
            inputCsrf.setAttribute("value", "{$smarty.session.csrf_token}");

            var logoutForm = document.createElement("form");
            logoutForm.action = '{$BASE_URL}action/users/logout.php';
            logoutForm.target = "_self";
            logoutForm.method = "POST";

            var body = document.body;
            
            body.appendChild(logoutForm);
            logoutForm.appendChild(inputCsrf);
            logoutForm.submit();
            return false;
        }
    </script>
    {/if}
    
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {if !isset($smarty.session.username)}
            <a class="navbar-brand" href="{$BASE_URL}pages/home.php">EventBook</a>
            {else}
                <a class="navbar-brand" href="{$BASE_URL}pages/event/explore_events.php">EventBook</a>
            {/if}
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav">
                <li {if $currentPage=="explore_events"} class="active" {/if}>
                    <a href="{$BASE_URL}pages/event/explore_events.php">Explore</a>
                </li>
                {if isset($smarty.session.username)}
                    <li {if $currentPage=="my_events" } class="active" {/if}>
                        <a href="{$BASE_URL}pages/event/my_events.php">My Events</a>
                    </li>
                    <li {if $currentPage=="create_event"} class="active" {/if} >
                        <a href="{$BASE_URL}pages/event/create_event.php">Create</a>
                    </li>
                {/if}
                <li {if $currentPage=="top_events"} class="active" {/if}>
                    <a href="{$BASE_URL}pages/event/top_events.php">Top</a>
                </li>
            </ul>


            <ul class="nav navbar-nav navbar-right">
                {if !isset($smarty.session.username)}
                    {if $currentPage=="register"}
                        <li class="active">
                            {else}
                        <li><a href='{$BASE_URL}pages/users/register.php'>Register</a></li>
                    {/if}

                    {if $currentPage=="login"}
                        <li class="active">
                            {else}
                        <li><a href='{$BASE_URL}pages/users/login.php'>Login</a></li>
                    {/if}
                {else}
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true'
                           aria-expanded='false'>
                            <img class='img-circle avatar-nav'
                                 src='{$BASE_URL}{$smarty.session.foto}'>{$smarty.session.username}<span
                                    class='caret'></span>
                        </a>
                        <ul class='dropdown-menu'>
                            <li><a href='{$BASE_URL}pages/users/profile.php'>Profile</a></li>
                            <li><a href='{$BASE_URL}pages/users/settings.php'>Settings</a></li>
                            <li role='separator' class='divider'></li>
                            <li><a onclick="logout();" id="logout" href="#">Logout</a></li>
                        </ul>
                    </li>
                {/if}
            </ul>
        </div>

    </div>
</nav>
{if isset($ERROR_MESSAGES)}
    <div id="error_messages">
    {foreach $ERROR_MESSAGES as $message}
        <div class="alert alert-danger alert-dismissible" role="alert" id="error_message">
            <button type="button" class="close" onclick="$('#error_message').fadeOut()" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <strong><center>{$message}</center></strong>
        </div>
    {/foreach}
    </div>
{/if}
{if isset($SUCCESS_MESSAGES)}
    <div id="success_messages">
        {foreach $SUCCESS_MESSAGES as $message}
            <div class="alert alert-success alert-dismissible" role="alert" id="success_message">
                <button type="button" class="close" onclick="$('#success_message').fadeOut()" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <strong><center>{$message}</strong></center>
            </div>
        {/foreach}
    </div>
{/if}


