<?
session_start();
include_once('status_messaging.php');
include_once('database/connection.php');
include_once('database/users.php');

if(isset($_GET['idThread'])){
	// Insert new comment
  if(isset($_GET['commentText'])){
    $idThread=$_GET['idThread'];
    $idOwner=$_SESSION['userId'];
    $commentText=$_GET['commentText'];
    // Database access
    $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO EventThreadComment VALUES(NULL,?,?,?)');
  	$stmt->execute(array($idThread,$idOwner,$commentText));
  }
  // Show comments
  try{
    $idThread=$_GET['idThread'];
    // Database access
    $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM EventThreadComment WHERE idEventThread = ? ');
    $stmt->execute(array($idThread));
    $comments = $stmt->fetchAll();
    foreach ($comments as &$comment) {
      $comment['userName']=getUserNameById($comment['idOwner']);
    }
    echo json_encode($comments);
  } catch (PDOException $e) {
    add_error_message($e->getMessage());
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
}
?>
