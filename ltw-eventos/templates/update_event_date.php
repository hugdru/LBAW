<label class="update-form">Change Date
<form action="action_update_date.php" method="post">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <label>Date:
    <input type="date" name="date" value="">
  </label>
  <input type="submit" value="Update">
</form>
<label>
