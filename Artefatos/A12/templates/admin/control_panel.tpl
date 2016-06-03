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
                            <button class="btn btn-default btn-xs" type="submit"><span class="glyphicon glyphicon-remove"></span> Remove</button>
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
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Privacy</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$list item='item'}
                    <tr>
                        <td>{$item['idevento']}</td>
                        <td>{$item['titulo']}</td>
                        <td>{$item['descricao']}</td>
                        <td>{if $item['publico'] eq 1}<span class="glyphicon glyphicon-eye-open"></span> Public{else}<span class="glyphicon glyphicon-eye-close"></span> Private{/if}</td>
                        <td>
                            <form onsubmit="return confirm('Do you really want to delete this event and all its contents?');" action="{$action_event_remove}" method="post">
                                <input type="hidden" name="idevento" value="{$item['idevento']}">
                                <a class="btn btn-default btn-xs" href="{$BASE_URL}pages/event/view_event.php?id={$item['idevento']}"><span class="glyphicon glyphicon-search"></span> View</a> 
                                <button class="btn btn-default btn-xs" type="submit"><span class="glyphicon glyphicon-remove"></span> Remove</button>
                            </form>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
{/if}

{if $option eq 3}
    <div class="panel_list">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Event</th>
                    <th>Parent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$list item='item'}
                    <tr>
                        <td>{$item['idcomentario']}</td>
                        <td>{$item['texto']}</td>
                        <td>{$item['username']}</td>
                        <td>{$item['titulo']}</td>
                        <td>{$item['idcomentariopai']}</td>
                        <td>
                            <form onsubmit="return confirm('Do you really want to delete this comment and its nested comments?');" action="{$action_comment_remove}" method="post">
                                <input type="hidden" name="idcomentario" value="{$item['idcomentario']}">
                                <a class="btn btn-default btn-xs" href="{$BASE_URL}pages/event/view_event.php?id={$item['idevento']}"><span class="glyphicon glyphicon-search"></span> View Context</a> 
                                <button class="btn btn-default btn-xs" type="submit"><span class="glyphicon glyphicon-remove"></span> Remove</button>
                            </form>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
{/if}

{if $option eq 4}
    <div class="panel_list">
        <div class="row">            
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
        </div> 
    </div>
{/if}

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>
