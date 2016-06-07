<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->
<div class="row">
    <div id="event-imgbox" class="col-md-4">
        <h2>{$event.titulo|escape:'html'}</h2>
        <img src="{$BASE_URL}{$event.capa}" class="img-thumbnail" alt="Capa" width="450" height="400">
    </div>
    <div id="event-detailbox" class="col-md-8">
        <h2>Details</h2>
        <div class="row">
            <div class="col-sm-6">
                <label for="timeanddate"> <i class="glyphicon glyphicon-time"></i> Time & Date</label>
                <p id="timeanddate">{$event.datainicio|escape:'html'|date_format:"%H:%M, %A, %B %e, %Y"}</p>
            </div>
            <div class="col-sm-6">
                <label for="location"><i class="glyphicon glyphicon-map-marker"></i> Location</label>
                <p id="location">{$event.localizacao|escape:'html'} <a href="#">(View in Map)</a></p>
            </div>
        </div>
        <label for="hosts"> <i class="glyphicon glyphicon-user"></i> Host(s)</label>
        <p id="hosts">{for $i=0 to $hosts|@count-1}{$hosts[$i]["nome"]|escape:'html'}{if $i != $hosts|@count-1}, {/if}{/for}</p>
        <label for="description"><i class="glyphicon glyphicon-comment"></i> Description</label>
        <p id="description">
            {$event.descricao|escape:'html'}
        </p>
        <div class="row">
            <div class="col-sm-6">
                <label for="attenders"><i class="glyphicon glyphicon-user"></i> Participants</label>
                <p id="attenders">{$number_part} People have joined this event <a href="#">(List)</a></p>
            </div>
            <div class="col-sm-6">
                <label for="share"><i class="glyphicon glyphicon-link"></i> Share</label>
                <p id="share"><a href="#">Facebook</a>, <a href="#">Email</a></p>
            </div>
        </div>
    </div>
</div>

<h2><i class="glyphicon glyphicon-comment"></i> Comments</h2>
<table>
    {foreach $comments as $comm}
    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='{$BASE_URL}{$comm["foto"]}'>
                <p>{$comm["username"]|escape:'html'}</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">{$comm["username"]|escape:'html'}</strong>
                {$comm["texto"]|escape:'html'}
            </div>
        </td>
    </tr>
    {/foreach}

    {if isset($smarty.session.username)}
    <!-- POST COMMENT AREA -->
    <tr>
        <td colspan="2" class="text-right">
            <p><textarea style="min-height: 100px;" class="form-control" placeholder="New Comment..."></textarea></p>
            <p>
                <button class="btn btn-primary">Post</button>
            </p>
        </td>
    </tr>
    {/if}

</table>
<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
