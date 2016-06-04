<!DOCTYPE html>
<html>
    <head>
        {include file='common/head.tpl'}
        <link rel="stylesheet" href="{$BASE_URL}css/home.css">
        <script src="{$BASE_URL}js/users/register.js"></script>
    </head>
    <body>
	{include file='common/navbar.tpl'}
	{include file='common/content-top.tpl'}     
        <!-- Content Start -->
        

        <div class="accountfield">
            <h1 style="padding: 0px; margin: 0px; margin-bottom: 20px">Register Account</h1> 
            
            <form role="form" action="{$action}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name" style="width: 100%">
                        Name
                        <a class="pull-right btn btn-default btn-xs" href="{$fblink}"><img style="height: 1.5em; vertical-align: top;" src="{$BASE_URL}images/facebook.png"> Import data from Facebook</a>
                    </label>
                    <input name="nome" type="text" class="form-control" id="name" {if $name}value="{$name}"{/if}>
                </div>
		
                <div id="username_group" class="form-group">
                    <label id="username_label" for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username">
                </div>
                
                <div class="form-group">
                    <label for="eml">Email Address</label>
                    <input name="email" type="email" class="form-control" id="email" {if $email}value="{$email}"{/if}>
                </div>
                
                <div class="form-group password_group">
                    <label for="pwd1" id="password_label">Password</label>
                    <input name="password" type="password" class="form-control" id="password">
                </div>
                
                <div class="form-group password_group">
                    <label for="pwd2">Password (Repeat)</label>
                    <input name="repeat_password" type="password" class="form-control" id="passwordRepeat">
                </div>
		
		<div class="form-group">
                    <label for="file">Profile photo</label>
                    {if $photo}
                        <input type="hidden" name="facebook_photo" value="{$photo}">
                        <img class="img-circle" style="width: 100px; height: 100px" src="{$photo}">
                    {else}
                        <input type="file" name="file" placeholder="Optional">
                    {/if}
                </div>

		<div class="form-group">
                    <label for="country">Your country:</label>                    
                    <select name="pais" id="country" class="form-control">
                        {foreach from=$list item='item'}
                            {if $item['idpais'] eq 180}
                                <option selected="selected" value='{$item["idpais"]}'>{$item["nome"]}</option>
                            {else}
                                <option value='{$item["idpais"]}'>{$item["nome"]}</option>
                            {/if}
                        {/foreach}
                    </select>
		</div>
                
                <button type="submit" class="btn btn-primary btn-block">Register</button>                
            </form>
        </div>

        <!-- Content Finish -->
	{include file = 'common/content-bottom.tpl'}
    </body>
</html>


