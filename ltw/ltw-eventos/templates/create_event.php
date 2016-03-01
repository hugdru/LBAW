<section class="header-form">
  <h2 class="form-title">Create a New Event</h2>
  <form id="create-form" action="action_create_event.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idOwner" value="<?=$_SESSION['userId']?>" >

    <label>Event Name
      <input type="text" name="name" value="" placeholder="Event Name">
    </label>

    <label>Date
      <input type="date" name="date" value="2015-01-01">
    </label>

    <label>Description
      <input type="text" name="description" value="" placeholder="Event Description">
    </label>

    <label>Type
      <input type="text" name="type" value="" placeholder="Type of event">
    </label>

    <label>Location
      <input type="text" name="location" value="" placeholder="Location of the event">
    </label>

    <label>Visibility
      <select name="visibility">
        <option value="1">Public</option>
        <option value="0">Private</option>
      </select>
    </label>

    <label>Image Title
      <input type="text" name="title" placeholder="Event cover image title">
    </label>
    <input type="file" name="image">
    <input type="submit" value="Create">
  </form>
</section>
