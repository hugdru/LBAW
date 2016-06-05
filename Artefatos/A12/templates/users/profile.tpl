<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="profile"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->
<div class="row">
    <div id="profile-imgbox" class="col-md-4">
        <img class="img-responsive img-circle" style="min-width: 100%" src="{$BASE_URL}{$smarty.session.foto}"/>
    </div>
    <div id="profile-detailbox" class="col-md-8">
        <h2>{$smarty.session.nome}</h2>
        <p id="desc">Hello, i'm a test user for EventBook</p>

        <label for="region"> <i class="glyphicon glyphicon-map-marker"></i> Country</label>
        <p id="region">{$smarty.session.idpais}</p>

        <label for="since"> <i class="glyphicon glyphicon-time"></i> Member Since</label>
        <p id="since">{$smarty.session.datacriacao}</p>

        <label for="since"> <i class="glyphicon glyphicon-stats"></i> Statistics</label>
        <p id="since">Events: Joined 50, Hosted 10</p>
    </div>
</div>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
