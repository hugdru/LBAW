<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl" title="login"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file="common/content-top.tpl"}
<!-- Content Start -->

<div class="accountField">
    <h1>Login</h1>

    <form role="form" action="{$action}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="user">Username</label>
            <input type="text" class="form-control" id="user" name="{$actionVars['username']}">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="{$actionVars['password']}">
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
