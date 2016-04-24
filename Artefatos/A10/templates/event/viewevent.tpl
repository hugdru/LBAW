<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <link rel="stylesheet" href="res/css/home.css">
</head>
<body>
{include file='common/navbar.tpl'}
{include file='common/content-top.tpl'}
<!-- Content Start -->
<div class="row">
    <div id="event-imgbox" class="col-md-4">
        <h2>Example Event</h2>
    </div>
    <div id="event-detailbox" class="col-md-8">
        <h2>Details</h2>
        <div class="row">
            <div class="col-sm-6">
                <label for="timeanddate"> <i class="glyphicon glyphicon-time"></i> Time & Date</label>
                <p id="timeanddate">December 35, 18:00</p>
            </div>
            <div class="col-sm-6">
                <label for="location"><i class="glyphicon glyphicon-map-marker"></i> Location</label>
                <p id="location">FEUP, Porto, Portugal <a href="#">(View in Map)</a></p>
            </div>
        </div>
        <label for="hosts"> <i class="glyphicon glyphicon-user"></i> Host(s)</label>
        <p id="hosts">Esteves Promotor, Manel Moderador</p>
        <label for="description"><i class="glyphicon glyphicon-comment"></i> Description</label>
        <p id="description">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed odio ipsum, dapibus vel hendrerit eu, rhoncus a nisi.
            Donec quis dolor non lectus pretium faucibus in et risus. Quisque in est metus. In ullamcorper dapibus fermentum.
            Sed tincidunt metus a ipsum dictum, eu hendrerit nunc ultricies. Sed et enim in nibh fringilla ullamcorper tempus nec felis.
            Phasellus porttitor sit amet ligula in luctus. Sed hendrerit arcu ac velit laoreet lacinia.
        </p>
        <div class="row">
            <div class="col-sm-6">
                <label for="attenders"><i class="glyphicon glyphicon-user"></i> Participants</label>
                <p id="attenders">200 People have joined this event <a href="#">(List)</a></p>
            </div>
            <div class="col-sm-6">
                <label for="share"><i class="glyphicon glyphicon-link"></i> Share</label>
                <p id="share"><a href="#">Facebook</a>, <a href="#">Email</a></p>
            </div>
        </div>
    </div>
</div>

<h2>Comments</h2>
<table>
    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='<?php echo $root; ?>/res/img/avatar_default.png'>
                <p>Peter Commenter</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">Peter Commenter</strong>
                Good Stuff
            </div>
        </td>
    </tr>

    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='<?php echo $root; ?>/res/img/avatar_default.png'>
                <p>Professor Prototipe</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">Professor Prototipe</strong>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam convallis nec lectus id congue. Ut at velit lectus. Suspendisse feugiat ante in nibh dignissim, et consequat neque commodo. Sed at erat eget mauris facilisis sagittis ut quis lorem. Nam eu.
            </div>
        </td>
    </tr>

    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='<?php echo $root; ?>/res/img/avatar_default.png'>
                <p>May Pockets</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">May Pockets</strong>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In eget justo magna. Praesent pellentesque dignissim felis, nec rutrum libero aliquam pulvinar. In hac habitasse platea.
            </div>
        </td>
    </tr>

    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='<?php echo $root; ?>/res/img/avatar_default.png'>
                <p>Mr Mister</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">Mr Mister</strong>
                Hello<br>
                World
            </div>
        </td>
    </tr>

    <tr>
        <td class="hidden-xs text-center">
            <div class="comment-holder">
                <img class='img-circle avatar' src='<?php echo $root; ?>/res/img/avatar_default.png'>
                <p>Example Guy</p>
            </div>
        </td>
        <td>
            <div class="comment">
                <strong class="visible-xs">Example Guy</strong>
                Example Comment
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="text-right">
            <p><textarea style="min-height: 100px;" class="form-control" placeholder="New Comment..."></textarea></p>
            <p><button class="btn btn-primary">Post</button></p>
        </td>
    </tr>

</table>
<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
