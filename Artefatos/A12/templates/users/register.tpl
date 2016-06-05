<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="register"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
    <script src="{$BASE_URL}js/users/register.js"></script>
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<div class="accountField">
    <h1 style="padding: 0px; margin: 0px; margin-bottom: 20px">Register Account</h1>

    <form role="form" action="{$action}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name" style="width: 100%">
                Name
                <a class="pull-right btn btn-default btn-xs" href="{$fblink}"><img
                            style="height: 1.5em; vertical-align: top;" src="{$BASE_URL}data/default/facebook.png"> Import
                    data from Facebook</a>
            </label>
            <input name="nome" type="text" class="form-control" id="name" {if $name}value="{$name}"{/if}>
        </div>

        <div id="username_group" class="form-group">
            <label id="username_label" for="username">Username</label>
            <input name="{$actionVars['username']}" type="text" class="form-control" id="username">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input name="{$actionVars['email']}" type="email" class="form-control" id="email" {if $email}value="{$email}"{/if}>
        </div>

        <div class="form-group password_group">
            <label for="password" id="password_label">Password</label>
            <input name="{$actionVars['password']}" type="password" class="form-control" id="password">
        </div>

        <div class="form-group password_group">
            <label for="passwordRepeat">Password (Repeat)</label>
            <input name="{$actionVars['passwordRepeat']}" type="password" class="form-control" id="passwordRepeat">
        </div>

        <div class="form-group">
            <label for="file">Profile photo</label>
            {if $photo}
                <input type="hidden" name="facebook_photo" value="{$photo}">
                <img class="img-circle" style="width: 100px; height: 100px" src="{$photo}">
            {else}
                <input type="file" name="{$actionVars['file']}" placeholder="Optional" id="file">
            {/if}
        </div>

        <div class="form-group">
            <label for="country">Your country:</label>
            <select name="{$actionVars['country']}" id="country" class="form-control">
                {foreach from=$countryList item='country'}
                    {if $country['idpais'] eq 180}
                        <option selected="selected" value='{$country["idpais"]}'>{$country["nome"]}</option>
                    {else}
                        <option value='{$country["idpais"]}'>{$country["nome"]}</option>
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


