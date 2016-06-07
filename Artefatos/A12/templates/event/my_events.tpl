<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="My Events"}
    <script src="{$BASE_URL}js/event/my_events.js"></script>
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>My Events</h1>
<p>
    <small>View and manage the events you joined</small>
</p>

<input type="text" class="form-control" placeholder="Search Events" id="search_bar">
<div id="search_results">

</div>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>