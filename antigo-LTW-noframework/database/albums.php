<?

function getAlbumByID($id){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM Album WHERE idAlbum = ?');
  $stmt->execute(array($id));
  $result = $stmt->fetch();
  return $result;
}

function getAlbumsByEventID($eventId){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM Album WHERE idEvent = ?');
  $stmt->execute(array($eventId));
  $result = $stmt->fetchAll();
  return $result;
}

?>
