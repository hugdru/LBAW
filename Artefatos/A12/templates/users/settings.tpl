<!DOCTYPE html>
<html>
<head>
    {include file="common/head.tpl" title="settings"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file="common/content-top.tpl"}
<!-- Content Start -->
{if $passwordReply === "0"}
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Password Update</strong> : Successful
    </div>
{elseif $passwordReply === "1"}
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Password Update Error</strong> : You can only change your password
    </div>
{elseif $passwordReply === "2"}
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Password Update Error</strong> : New Passwords Mistmatch
    </div>
{elseif $passwordReply === "3"}
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Password Update Error</strong> : Original Password is not correct
    </div>
{elseif $passwordReply === "4"}
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Password Update Error</strong> : New password should have between 8 and 100 characters
    </div>
{/if}

{if $pictureReply === "0"}
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Picture Update</strong> : Successful
</div>
{elseif $pictureReply === "1"}
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Picture Update Error</strong> : You have to choose the picture
</div>
{elseif $pictureReply === "2"}
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Picture Update Error</strong> : Picture size not valid. Upload files only with size under 10Mb
    </div>
{/if}


<h1>Settings</h1>

<div class="row">
    <div class="col-sm-4">
        <form method="post" action="{$actionUpdatePhoto}" role="form" enctype="multipart/form-data">
            <h3>Change Picture</h3>
            <input type="hidden" name="{$actionUpdatePhotoVars["idutilizador"]}"
                   value="{$smarty.session.idutilizador}">

            <div class="form-group">
                <label for="currentPicture">Current Picture</label>
                <img class="img-responsive img-circle" style="min-width: 100%" src="{$BASE_URL}{$smarty.session.foto}"/>
            </div>

            <div class="form-group">
                <label for="newPicture">New Picture</label>
                <div class="form-group">
                    <input type="file" name="{$actionUpdatePhotoVars["newPhoto"]}" id="newPicture">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <div class="col-sm-4">
        <form method="post" action="{$actionUpdateDescription}" role="form" enctype="multipart/form-data">
            <h3>Profile: Description</h3>
            <div class="form-group">
                <textarea style="min-height: 100px; resize: none;" class="form-control" id="dsc" name="{$actionUpdateDescriptionVars["newDescription"]}" placeholder="{$smarty.session.descricao}"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <div class="col-sm-4">
        <form role="form">
            <h3>Profile: Region</h3>
            <div class="form-group">
                <input type="text" class="form-control" id="rgn" value="Portugal">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <form role="form">
            <h3>Change Email Address</h3>
            <div class="form-group">
                <label for="eml">Email Address</label>
                <input type="email" class="form-control" id="eml" value="someone@somewhere.whom">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
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

            <button type="submit" class="btn btn-primary">Save</button>
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
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<!-- Content Finish -->
{include file="common/content-bottom.tpl"}
</body>
</html>
