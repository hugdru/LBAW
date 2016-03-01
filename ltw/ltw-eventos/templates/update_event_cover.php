<label class="update-form">Change Cover
<form action="action_update_cover.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <label>Image Title:
    <input type="text" name="title">
  </label>
  <input type="file" name="image">
  <input type="submit" value="Update">
</form>
<label>
