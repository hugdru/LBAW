<!DOCTYPE html>
<html>
<head>
    {include file='common/head.tpl'}
    <script type="text/javascript" src="{$BASE_URL}js/event/create_event.js"></script>
</head>
<body>
{include file='common/navbar.tpl' currentPage="$currentPage"}
{include file='common/content-top.tpl'}
<!-- Content Start -->

<h1>Create Event</h1>

<form role="form" onsubmit="return handleForm();" >
    <div class="form-group">
        <label for="title">Event Title</label>
        <input type="text" class="form-control" id="title" name="{$actionVars['title']} required">
    </div>

    <div class="form-group">
        <label>Cover Picture</label>
        <input type="file" name="{$actionVars['file']}" class="form-control-static">
    </div>

    <div class="form-group">
        <label>Event Privacy</label>
        <div class="radio">
            <label>
                <input type="radio" name="{$actionVars['public']}" value="true" checked="checked">
                Public<br>
                <small>Viewable in the explore page, and joinable by anyone.</small>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="{$actionVars['public']}" value="false">
                Private<br>
                <small>Users cannot view or join the event unless invited by a host.</small>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>Event Date</label>
        <div style="overflow:hidden;">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div id="eventDateTimePicker"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea style="min-height: 200px; resize: none;" class="form-control" id="description" name="{$actionVars['description']}"></textarea>
    </div>

    <div class="form-group">
        <label id="location_label" for="location">Location</label>
        <input name="{$actionVars['location']}" type="text" class="form-control" id="location">
    </div>

    <div class="poll-submit">
        <input type="hidden" name="{$actionVars['csrf']}" value="{$smarty.session.csrf_token}">
        <button type="submit" class="btn btn-primary">Create Event</button>
    </div>
</form>

<!-- Content Finish -->
{include file='common/content-bottom.tpl'}
</body>
</html>
