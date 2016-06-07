<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl' title="Top Events"}
    <script src="{$BASE_URL}js/event/top_events.js"></script>
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>Top Events</h1>
<p>
    <small>Here are some events that you might like</small>
</p>

<div id="search_results">
</div>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>