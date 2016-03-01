<script src="https://maps.googleapis.com/maps/api/js"></script>
<?
  session_start();
  include_once('status_messaging.php');

  if (!isset($_POST['idOwner'])) add_error_message('User ID is mandatory.');
  if (!isset($_POST['name']) || trim($_POST['name']) == '') add_error_message('Event Name is mandatory.');
  if (!isset($_POST['date']) || trim($_POST['date']) == '') add_error_message('Date is mandatory.');
  if (!isset($_POST['description']) || trim($_POST['description']) == '') add_error_message('Description is mandatory.');
  if (!isset($_POST['type']) || trim($_POST['type']) == '') add_error_message('Type is mandatory.');
  if (!isset($_POST['visibility']) || trim($_POST['visibility']) == '') add_error_message('Visibility is mandatory.');
  if (!isset($_POST['location']) || trim($_POST['location']) == '') add_error_message('Location is mandatory.');
  if (!isset($_FILES["image"]["tmp_name"])) add_error_message('Image file is mandatory');
  if (!isset($_POST['title']) || trim($_POST['title']) == '') add_error_message('Image Title is mandatory.');

  include_once('database/connection.php');
  include_once('database/events.php');
  //window.history.go(-1) lets browser start forms with the input values before submit was called
  if(isset($_SESSION['error_message'])){?>
    <script>
    window.history.go(-1);
    </script>
  <?}
  else{
    //makes json request to google maps api, asking if location string is a valid geocode. if not, outputs an error message
    $result=file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . urlencode($_POST['location']) . "&sensor=false" );
    $geocodedinfo=json_decode($result);
    if($geocodedinfo->status !== 'OK'){
      add_error_message('Invalid geocode location');?>
      <script>
      window.history.go(-1);
      </script>
    <?
      exit();
    }
else{
    try {
    	// Database access
      $idEvent=createEvent($_POST['idOwner'], $_POST['name'], $_POST['date'], $_POST['description'],$_POST['location'], $_POST['type'], $_POST['visibility'], $_POST['title']);
    }
    catch (PDOException $e) {
      add_error_message($e->getMessage());
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    add_success_message('Event successfully created!');
    header('Location: view_event.php?id=' . $idEvent);
  }
  }
?>
