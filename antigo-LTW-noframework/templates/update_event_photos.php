<label class="update-form">Add photo
<form action="action_add_event_photo.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <label>Image Title:
    <input type="text" name="title">
  </label>
  <input type="file" name="image">
  <input type="submit" value="Add photo">
</form>
<label>
