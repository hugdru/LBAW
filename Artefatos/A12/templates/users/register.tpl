<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="register"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
    <script src="{$BASE_URL}js/users/register.js"></script>
</head>
<body>
{include file='common/navbar.tpl'}
{include file='common/content-top.tpl'}
<!-- Content Start -->


<div class="accountField">
    <h1>Register Account</h1>

    <form role="form" action="{$action}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input name="{$actionVars['name']}" type="text" class="form-control" id="name">
        </div>

        <div id="username_group" class="form-group">
            <label id="username_label" for="username">Username</label>
            <input name="{$actionVars['username']}" type="text" class="form-control" id="username">
        </div>

        <div class="form-group">
            <label for="eml">Email Address</label>
            <input name="{$actionVars['email']}" type="email" class="form-control" id="email">
        </div>

        <div class="form-group password_group">
            <label for="pwd1" id="password_label">Password</label>
            <input name="{$actionVars['password']}" type="password" class="form-control" id="password">
        </div>

        <div class="form-group password_group">
            <label for="pwd2">Password (Repeat)</label>
            <input name="{$actionVars['passwordRepeat']}" type="password" class="form-control" id="passwordRepeat">
        </div>

        <div class="form-group">
            <label for="file">Profile photo</label>
            <input type="file" name="{$actionVars['file']}" placeholder="Optional">
        </div>

        <div class="form-group">
            <label for="country">Your country:</label>
            <select name="{$actionVars['country']}" id="country" class="form-control">
                <option value="180">Portugal</option>
                <option value="10">Country ID:10</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<!-- Content Finish -->
{include file = 'common/content-bottom.tpl'}
</body>
</html>


