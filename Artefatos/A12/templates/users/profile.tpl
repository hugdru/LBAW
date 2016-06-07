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
{if $user}
<div class="row">
    <div id="profile-imgbox" class="col-md-4">
        <img class="img-responsive img-circle" style="min-width: 100%" src="{$BASE_URL}{$user["foto"]}"/>
    </div>
    <div id="profile-detailbox" class="col-md-8">
        <h2>{$user["nome"]}</h2>
        <!--                                           CORRIGIR BUG DA DESCRIÇAO NAO FAZER LINE BREAKING!!!!!!!!!!   -->
        <!--<button type="submit" class="btn btn-primary btn-block">Edit Profile</button>-->
        <label for="region"> <i class="glyphicon glyphicon-pencil"></i> Description</label>
        <<p id="region">{$user["descricao"]}</p>

        <label for="region"> <i class="glyphicon glyphicon-map-marker"></i> Country</label>
        <p id="region">{$user["pais"]}</p>

        <label for="since"> <i class="glyphicon glyphicon-time"></i> Member Since</label>
        <p id="since">{$user["datacriacao"]}</p>

        <label for="email"> <i class="glyphicon glyphicon-envelope"></i> Email</label>
        <p id="email">{$user["email"]}</p>


        <label for="since"> <i class="glyphicon glyphicon-stats"></i> Statistics</label>
        <!-- CONTAR EVENTOS JOINED E EVENTOS HOSTED PARA DISPLAY (SQL) -->
        <p id="since">Events: Joined 50, Hosted 10</p>
    </div>
</div>
{else}   
<div class="row">
    <div id="profile-imgbox" class="col-md-4">
        <img class="img-responsive img-circle" style="min-width: 100%" src="{$BASE_URL}{$smarty.session.foto}"/>
    </div>
    <div id="edit">
        <a type="button" role="button" href="{$settings}" class="btn btn-info" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-pencil"></i>Edit Profile</a>
    </div>

    <div id="profile-detailbox" class="col-md-8">
        <h2>{$smarty.session.nome}</h2>
        <!--                                           CORRIGIR BUG DA DESCRIÇAO NAO FAZER LINE BREAKING!!!!!!!!!!   -->
        <label for="region"> <i class="glyphicon glyphicon-pencil"></i> Description</label>
        <p id="region">{$smarty.session.descricao}</p>

        <label for="region"> <i class="glyphicon glyphicon-map-marker"></i> Country</label>
        <p id="region">{$smarty.session.pais}</p>

        <label for="since"> <i class="glyphicon glyphicon-time"></i> Member Since</label>
        <p id="since">{$smarty.session.datacriacao}</p>

        <label for="email"> <i class="glyphicon glyphicon-envelope"></i> Email</label>
        <p id="email">{$smarty.session.email}</p>


        <label for="since"> <i class="glyphicon glyphicon-stats"></i> Statistics</label>
        <!-- CONTAR EVENTOS JOINED E EVENTOS HOSTED PARA DISPLAY (SQL) -->
        <p id="since">Events: Joined {$joins}, Hosted {$hosts}</p>
        <!--<button type="button" class="btn btn-info" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-pencil"></i>Edit Profile</button>-->
    </div>
</div>
{/if}
        
<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
