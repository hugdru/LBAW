<!DOCTYPE html>
<html>
<head>
	<title>LTW Project</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="lightbox/css/lightbox.css">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="thread_comments.js"></script>
</head>
<body>
	<?
	// Show error messages
	if(isset($_SESSION['error_message']))
	foreach ($_SESSION['error_message'] as $message){?>
		<div class="error_message"><?=$message?><img src="images/icons/cancel.png"></div>
		<script>
		removeLine = function(e){
			$(this).parent().remove();
		}
		$('.error_message').children().last().click(removeLine);
		</script>
		<?}
		// Clean the error messages
		unset($_SESSION['error_message']);
		if(isset($_SESSION['success_message']))
		// Show success messages
		foreach ($_SESSION['success_message'] as $message){?>
			<div class="success_message"><h4><?=$message?></h4><img src="images/icons/cancel.png"></div>
			<script>
			removeLine = function(e){
				$(this).parent().remove();
			}
			$('.success_message').children().last().click(removeLine);
			</script>
			<?}
			// Clean the success messages
			unset($_SESSION['success_message']);

			// Check if user is logged in
			if(isset($_SESSION['username'])){?>
		<nav>
			<ul class="navigation">
				<li><a href="view_profile.php?userId=<?=$_SESSION['userId']?>">View Profile</a></li>
				<li><a href="create_event.php">Create Event</a></li>
				<li><a href="search_events.php">Search Event</a></li>
				<li><a href="action_logout.php">Logout</a></li>
			</ul>
		</nav>
		<?}?>
