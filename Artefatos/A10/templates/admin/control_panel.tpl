<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl"}
    <link rel="stylesheet" href="{$BASE_URL}css/admin.css">
</head>
<body>
{include file="common/content-top.tpl"}

<!-- Content Start -->
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
        Coming Soon
    </div>
{/if}

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>
