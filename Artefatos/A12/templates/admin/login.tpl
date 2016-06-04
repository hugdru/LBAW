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
    <h1 style="padding: 0px; margin: 0px; margin-bottom: 20px;">Login</h1>

    <form action="{$action}" method="post" role="form">
        <div class="form-group">
            <label for="usr">Username</label>
            <input name="admin_username" type="text" class="form-control" id="usr">
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input name="admin_password" type="password" class="form-control" id="pwd">
        </div>
        
        <div class="btn-group" style="width: 100%">
            <button type="submit" style="width: 80%" class="btn btn-primary">Login</button>
            <a class="btn btn-primary" style="width: 20%" href="{$BASE_URL}" title="Return to Eventbook">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            </a>
        </div>
    </form>
</div>

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>