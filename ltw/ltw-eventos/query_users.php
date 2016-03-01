<?
include_once('database/connection.php');
try {
  $stmt = Database::getInstance()->getConnection()->prepare("SELECT idUser, idProfPic, name FROM User WHERE name LIKE ? AND idUser != ?
    AND idUser NOT IN (SELECT DISTINCT idUser FROM InvitedEvent WHERE idEvent = ?)
    LIMIT 5");
  $stmt->execute(array($_POST['queryText'] . '%', $_POST['userId'], $_POST['eventId']));
  echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
  die($e->getMessage());
}
?>
