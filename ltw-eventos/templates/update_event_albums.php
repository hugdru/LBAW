<label class="update-form">Add album
<form action="action_add_event_album.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=$_GET['id']?>" >
  <label>Album Title:
    <input type="text" name="title">
  </label>
  <input name="album[]" type="file" accept=".gif,.jpeg,.jpg,.bmp,.png" multiple>
  <input type="submit" value="Add album">
</form>
<label>
