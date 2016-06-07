<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="Explore Events"}
    <script src="{$BASE_URL}js/event/explore_events.js"></script>
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>Explore Events</h1>
<p>
    <small>Here's some public events that you may like</small>
</p>

<input type="text" class="form-control" placeholder="Search Events" id="search_bar">
<div id="search_results">

</div>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>