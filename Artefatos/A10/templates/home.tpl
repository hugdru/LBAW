<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
{include file='common/navbar.tpl'}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>Welcome</h1>
<p>Eventbook allows you to create and share events for any occasion!</p>
<p>Creating an account is easy! And it only takes a minute or two.</p>

<p id="home-loginbox">
    <a href="../pages/users/register.php"><button class="btn btn-default">Register</button></a>
    <a href="../pages/users/login.php"><button class="btn btn-primary">Login</button></a>
    <br>
    <small>You can also start to explore public events by <a href="../pages/event/explorevent.php">clicking here</a></small>.
</p>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>