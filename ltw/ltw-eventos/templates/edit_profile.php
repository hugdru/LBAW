<ul class="navigation">
	<li><a href="update_profile_pic.php">Update profile picture</a></li>
	<li><a href="change_password.php">Change password</a></li>
	<li><a href="#" id="changeDescription">Change description</a></li>
</ul>
<form id="changeDescriptionForm" style="display: none" action="action_change_userDescription.php" method="post">
	<textarea name="newDescription" rows="5" cols="58" placeholder="New Description"><?=$user['description']?></textarea>
	<input type="submit" value="Change description">
</form>
<script>
showChangeDescription = function(){
	$('#changeDescriptionForm').toggle('fast');
}
$('#changeDescription').click(showChangeDescription);
</script>
