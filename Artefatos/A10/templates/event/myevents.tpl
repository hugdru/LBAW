<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
</head>
<body>
{include file='common/navbar.tpl'}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>My Events</h1>
<p><small>View and manage the events you joined</small></p>

<form role="search">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Events">
        <div class="input-group-btn">
            <button type="submit" class="btn btn-primary">
                <i class="glyphicon glyphicon-search"></i>
            </button>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 1</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>

    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 2</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>

    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 3</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 4</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>

    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 5</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>

    <div class="col-sm-4">
        <a href="/?id=404" class="eventbox">
        <h3 class="title">Subscribed Event 6</h3>
        <p class="details">Porto, 32 January</p>
        <p class="description">An example event, of no importance at all, without any random things to mention.</p>
        </a>
    </div>
</div>

<div class="text-center">
    <ul class="pagination">
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
    </ul>
</div>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>