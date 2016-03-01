<label class="update-form">Delete Event
<form action="action_delete_event.php" method="post">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <input type="submit" value="Delete">
</form>
<a href="view_event.php?id=<?=$_GET['id']?>"><button>Cancel</button></a>
<label>
