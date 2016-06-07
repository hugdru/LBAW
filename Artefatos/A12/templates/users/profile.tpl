<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="profile"}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
    <link rel="stylesheet" href="{$BASE_URL}css/profile.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->
<div class="row">
    <div id="profile-imgbox" class="col-md-4">
        <img class="img-responsive img-circle" style="min-width: 100%" src="{$BASE_URL}{$user["foto"]}"/>
    </div>
    {if $user.idutilizador == $smarty.session.idutilizador}
    <div id="edit">
        <a type="button" role="button" href="{$BASE_URL}pages/users/settings.php" class="btn btn-info" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-pencil"></i>Edit Profile</a>
    </div>
    {/if}
    <div id="profile-detailbox" class="col-md-8">
        <h2>{$user["nome"]}</h2>

        <label for="description"> <i class="glyphicon glyphicon-pencil"></i> Description</label>
        <p id="description">{$user["descricao"]}</p>

        <label for="region"> <i class="glyphicon glyphicon-map-marker"></i> Country</label>
        <p id="region">{$user["pais"]}</p>

        <label for="since"> <i class="glyphicon glyphicon-time"></i> Member Since</label>
        <p id="since">{$user["datacriacao"]}</p>

        <label for="email"> <i class="glyphicon glyphicon-envelope"></i> Email</label>
        <p id="email">{$user["email"]}</p>

        <label for="stats"> <i class="glyphicon glyphicon-stats"></i> Statistics</label>
        <p id="stats">Events: Joined {$user["joins"]}, Hosted {$user["hosts"]}</p>
    </div>
</div>
        
<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
