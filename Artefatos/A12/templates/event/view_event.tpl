<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <link rel="stylesheet" href="{$BASE_URL}css/home.css">
    <link rel="stylesheet" href="{$BASE_URL}css/event.css">
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
    {if $is_host}
        <!-- Button trigger modal -->
        <div id="cancel-event">
            <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#myModal" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-remove"></i>
                Cancel Event
            </button>
        </div>
        <div id="edit-event">
            <a type="button" role="button" href="{$BASE_URL}pages/users/edit_event.php'" class="btn btn-info" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-pencil"></i>Edit Event</a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Do you really want to cancel the event?</h4>
                    </div>
                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <form method="post" action="{$BASE_URL}action/event/cancel_event.php" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="{$actionCancelEventVars["idEvent"]}"
                                   value="{$idevent}">
                            <div id="cancel-event2">
                                <button type="submit" class="btn btn-primary" style="margin: 15px 150px;"> <i style="padding-right: 8%;" class="glyphicon glyphicon-remove"></i>Yes i want</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {/if}
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
            <label for="visibility">
                {if $event.publico}
                <i class="glyphicon glyphicon-eye-open"></i> Visibility</label>
                <p>This is a public event. Everyone can access it.</p>
                {else}
                <i class="glyphicon glyphicon-eye-close"></i> Visibility</label>
                <p>This is a private event. Only invited users can access it.</p>
                {/if}
    </div>
</div>
<div class="row">
{if $is_participant}

{elseif isset($smarty.session.username)}
    <label for="intention">Are you going to this event?</label>
    <div class="going">
        <form method="post" action="{$BASE_URL}action/event/setIntention.php" role="form" enctype="multipart/form-data">
            <input type="hidden" name="{$actionIntentionVar["idEvent"]}" value="{$event.idevento}">
            <button type="submit" class="btn btn-success glyphicon glyphicon-ok"> YES!</button>
        </form>
    </div>
</div>
{/if}

<h2><i class="glyphicon glyphicon-comment"></i> Comments</h2>
<table>
    {foreach $comments as $comm}
    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='{$BASE_URL}{$comm["foto"]}' alt='avatar'>
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
        <td colspan="2" class="text-right" style="width: 1000px">
            <form method="post" action="{$actionComment}" role="form" enctype="multipart/form-data">
                <input type="hidden" name="{$actionCommentVars["idEvent"]}"
                       value="{$event.idevento}">
                <p><textarea style="min-height: 100px;" class="form-control" name="{$actionCommentVars["newComment"]}" placeholder="New Comment..."></textarea></p>
                <p><button type="submit" class="btn btn-primary">Post</button></p>
            </form>
        </td>
    </tr>
    {/if}

</table>
<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
