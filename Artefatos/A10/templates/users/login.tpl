<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl"}
    <link rel="stylesheet" href="res/css/home.css">
</head>
<body>
{include file="common/navbar.tpl"}
{include file="common/content-top.tpl"}
<!-- Content Start -->


<div class="accountfield">
    <h1>Login</h1>

    <form role="form">
        <div class="form-group">
            <label for="usr">Username</label>
            <input type="text" class="form-control" id="usr">
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" class="form-control" id="pwd">
        </div>
        <div class="checkbox">
            <label><input type="checkbox"> Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>