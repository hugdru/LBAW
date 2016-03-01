<label class="update-form">Change Description
<form action="action_update_event_description.php" method="post">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <label>Description:
    <input type="text" name="description" value="" placeholder="New Description">
  </label>
  <input type="submit" value="Update">
</form>
<label>
