<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
</head>
<body>
{include file='common/navbar.tpl'}
{include file='common/content-top.tpl'}
<!-- Content Start -->
{if !isset($USERNAME)}
<h1>Welcome</h1>
<p>Eventbook allows you to create and share events for any occasion!</p>
<p>Creating an account is easy! And it only takes a minute or two.</p>

<p id="home-loginbox">
    <a href="{$BASE_URL}pages/users/register.php"><button class="btn btn-default">Register</button></a>
    <a href="{$BASE_URL}pages/users/login.php"><button class="btn btn-primary">Login</button></a>
    <br>
    <small>You can also start to explore public events by <a href="{$BASE_URL}pages/event/explore_events.php">clicking here</a></small>.
</p>
{else}
<p id="home-loginbox">
   <p>Go to <a href="{$BASE_URL}pages/event/explore_events.php">explore</a> section to check for upcoming events</p>
</p>
{/if}


<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
