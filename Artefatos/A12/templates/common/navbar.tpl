<nav id="menu" class="navbar navbar-inverse navbar-fixed-top">
    {if isset($smarty.session.username)}
    <script type="text/javascript">
        window.onload = function() {
            document.getElementById("logout").onclick=function() {
                var inputCsrf = document.createElement("input");
                inputCsrf.setAttribute("type", "hidden");
                inputCsrf.setAttribute("name", "csrf");
                inputCsrf.setAttribute("value", "{$smarty.session.csrf_token}");

                var logoutForm = document.createElement("form");
                logoutForm.action = '{$BASE_URL}action/users/logout.php';
                logoutForm.target = "_self";
                logoutForm.method = "POST";

                logoutForm.appendChild(inputCsrf);
                logoutForm.submit();
                return false;
            }
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

            <a class="navbar-brand" href="{$BASE_URL}index.php">EventBook</a>
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
                            <li><a id="logout">Logout</a></li>
                        </ul>
                    </li>
                {/if}
            </ul>
        </div>

    </div>
</nav>


