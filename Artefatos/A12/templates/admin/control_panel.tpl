<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl"}
    <link rel="stylesheet" href="{$BASE_URL}css/admin.css">
</head>
<body>
{include file="common/content-top.tpl"}

<!-- Content Start -->
{foreach from=$ERROR_MESSAGES item='item'}
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {$item}
</div>
{/foreach}

{foreach from=$SUCCESS_MESSAGES item='item'}
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {$item}
</div>
{/foreach}

<div id="header">
    <strong class="panel_title">
        Control Panel - {$account_email}
    </strong>

    <form class="pull-right" action="{$logout_action}">
        <button type="submit" class="btn btn-default">Logout</button>
    </form>
</div>
        
<div class="btn-group btn-group-justified panel_options">
    <a href="./control_panel.php?option=1" class="btn btn-primary {if $option eq 1} active {/if}">Users</a>
    <a href="./control_panel.php?option=2" class="btn btn-primary {if $option eq 2} active {/if}">Events</a>
    <a href="./control_panel.php?option=3" class="btn btn-primary {if $option eq 3} active {/if}">Comments</a>
    <a href="./control_panel.php?option=4" class="btn btn-primary {if $option eq 4} active {/if}">Administrators</a>
</div>

{if $option eq 1}
<div class="panel_list">       
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$list item='item'}
                <tr>
                    <td>{$item['idutilizador']}</td>
                    <td>{$item['nome']}</td>
                    <td>{$item['username']}</td>
                    <td>{$item['email']}</td>
                    <td>
                        <form onsubmit="return confirm('Do you really want to delete this user and all its contents?');" action="{$action_user_remove}" method="post">
                            <input type="hidden" name="idutilizador" value="{$item['idutilizador']}">
                            <button type="submit"><span class="glyphicon glyphicon-remove"></span> Remove</button>
                        </form>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{/if}

{if $option eq 2}
    <div class="panel_list">
        Coming Soon
    </div>
{/if}

{if $option eq 3}
    <div class="panel_list">
        Coming Soon
    </div>
{/if}

{if $option eq 4}
    <div class="panel_list">
        <div class="row">
            <div class="col-sm-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$list item='item'}
                            <tr>
                                <td>{$item['idadministrador']}</td>
                                <td>{$item['username']}</td>
                                <td>{$item['email']}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>                
            </div>
            <div class="col-sm-4">
                <form method="post" action="{$action_create_admin}" role="form" onsubmit="return confirm('Creating a new Administrator account, Continue?');">
                    <h4>Create a new Adminstrator Account</h4>

                    <div class="form-group">
                        <label for="cad_email">Email:</label>
                        <input class="form-control" id="cad_email" name="cad_email" type="text">
                    </div>
                    <div class="form-group">    
                        <label for="cad_username">Username:</label>
                        <input class="form-control" id="cad_username" name="cad_username" type="text">
                    </div>
                    <div class="form-group"> 
                        <label for="cad_password">Password:</label>
                        <input class="form-control" id="cad_password" name="cad_password" type="password">
                    </div>
                    <div class="form-group"> 
                        <label for="cad_password_repeat">Password (Repeat):</label>
                        <input class="form-control" id="cad_password_repeat" name="cad_password_repeat" type="password">
                    </div> 
                    <button type="submit" class="btn btn-primary btn-block">Create</button>
                </form>
            </div>
        </div>        
    </div>
{/if}

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>
