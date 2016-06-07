<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl" title="settings"}
    <link rel="stylesheet" href="{$BASE_URL}css/settings.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file="common/content-top.tpl"}
<!-- Content Start -->


<h1>Settings</h1>

<div class="row">
    <div class="col-sm-4">
        <form method="post" action="{$actionUpdatePhoto}" role="form" enctype="multipart/form-data">
            <h3>Change Picture</h3>
            <input type="hidden" name="{$actionUpdatePhotoVars["idutilizador"]}"
                   value="{$smarty.session.idutilizador}">

            <div class="form-group">
                <label for="currentPicture">Current Picture</label>
                <img class="img-circle st-profile-img center-block" src="{$BASE_URL}{$smarty.session.foto}"/>
            </div>

            <div class="form-group">
                <label for="newPicture">New Picture</label>
                <div class="form-group">
                    <input type="file" name="{$actionUpdatePhotoVars["newPhoto"]}" id="newPicture">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>

    <div class="col-sm-4">
        <form method="post" action="{$actionUpdateDescription}" role="form" enctype="multipart/form-data">
            <h3>Profile: Description</h3>
            <div class="form-group">
                <textarea class="form-control" id="dsc" name="{$actionUpdateDescriptionVars["newDescription"]}">{$smarty.session.descricao}</textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>

    <div class="col-sm-4">
        <form role="form" action="{$actionUpdateCountry}" method="post">
            <h3>Profile: Region</h3>
            <div class="form-group">
                <select name="{$actionUpdateCountryVars['newCountry']}" id="country" class="form-control">
                    {foreach from=$countryList item='country'}
                        {if $country['idpais'] eq $smarty.session.idpais}
                            <option selected="selected" value='{$country["idpais"]}'>{$country["nome"]}</option>
                        {else}
                            <option value='{$country["idpais"]}'>{$country["nome"]}</option>
                        {/if}
                    {/foreach}
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <form method="post" action="{$actionUpdateEmail}" role="form" enctype="multipart/form-data">
            <h3>Change Email Address</h3>
            <div class="form-group">
                <label for="eml">Email Address</label>
                <input type="email" class="form-control" id="eml" name="{$actionUpdateEmailVars["newEmail"]}" value="{$smarty.session.email}">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>

    <div class="col-sm-4">
        <form method="post" action="{$actionUpdatePassword}" role="form">
            <h3>Change Password</h3>
            <input type="hidden" name="{$actionUpdatePasswordVars["idutilizador"]}"
                   value="{$smarty.session.idutilizador}">

            <div class="form-group">
                <label for="originalPassword">Current Password</label>
                <input type="password" class="form-control" id="originalPassword"
                       name="{$actionUpdatePasswordVars["password"]}">
            </div>

            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword"
                       name="{$actionUpdatePasswordVars["newPassword"]}">
            </div>

            <div class="form-group">
                <label for="newRepeatPassword">New Password (Repeat)</label>
                <input type="password" class="form-control" id="newRepeatPassword"
                       name="{$actionUpdatePasswordVars["newRepeatPassword"]}">
            </div>

            <div class="poll-submit">
                <input type="hidden" name="{$actionUpdatePasswordVars['csrf']}" value="{$smarty.session.csrf_token}">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>

    <div class="col-sm-4">
        <form role="form">
            <h3>Notification Preferences</h3>
            <div class="form-group">
                <label>Send me a Email when</label>
                <div class="checkbox">
                    <label><input type="checkbox" value="">I'm invited to an Event</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="">An event i follow has updates</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="">EventBook has recommended events for me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>
</div>

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>
