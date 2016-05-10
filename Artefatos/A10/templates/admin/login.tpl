<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl"}
</head>
<body>
{include file="common/content-top.tpl"}

<!-- Content Start -->
{if $alert eq true}
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Login Failed</strong> : {$alert_message}
</div>
{/if}

<div class="accountfield">
    <h1>Login</h1>

    <form action="{$action}" method="post" role="form">
        <div class="form-group">
            <label for="usr">Username</label>
            <input name="admin_username" type="text" class="form-control" id="usr">
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input name="admin_password" type="password" class="form-control" id="pwd">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>