<?
include_once('database/images.php');

function getEventByID($id){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM Event WHERE idEvent = ?');
  $stmt->execute(array($id));
  return $stmt->fetch();
}

function getOwnerID($eventID){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idOwner FROM Event WHERE idEvent = ?');
  $stmt->execute(array($eventID));
  return $stmt->fetchColumn();
}

function updateEvent($id, $name, $eventDate, $description, $eventType, $visibility){
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET name = ?, eventDate = ?, description = ?, eventType = ?, visibility = ? WHERE idEvent = ?');
  $stmt->execute(array($name, $eventDate, $description, $eventType, $visibility, $id));
}

function updateEventCover($id,$imgDesc){
  $idImg = createCoverImage($imgDesc);
  deleteEventCover($id);
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET idCover = ? WHERE idEvent = ?');
  $stmt->execute(array($idImg,$id));
}

function updateEventDate($id,$date){
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET eventDate = ? WHERE idEvent = ?');
  $stmt->execute(array($date,$id));
}

function updateEventDescription($id,$desc){
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET description = ? WHERE idEvent = ?');
  $stmt->execute(array($desc,$id));
}

function updateEventVisibility($id,$visib){
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET visibility = ? WHERE idEvent = ?');
  $stmt->execute(array($visib,$id));
}

function addEventPhoto($id,$imgDesc){
  $idImg = createImage($imgDesc);
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Image SET idEvent = ? WHERE idImage = ?');
  $stmt->execute(array($id,$idImg));
}

function addEventAlbum($id,$albumTitle){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO Album VALUES(NULL, ?, ?)');
  $stmt->execute(array($id,$albumTitle));
  $idAlbum = Database::getInstance()->getConnection()->lastInsertId();

  createAlbum($idAlbum,$id,$albumTitle);
}

function createEvent($idOwner, $name, $eventDate, $description,$location, $eventType, $visibility, $imgDesc){
  //create image
  $idImg = createCoverImage($imgDesc);

  //create event
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO Event VALUES(NULL, ?, ?, ?, ?, ?, ?, ?,?)');
  $stmt->execute(array(-1,$idOwner, $name, $eventDate, $description,$location, $eventType, $visibility));
  $idEvent = Database::getInstance()->getConnection()->lastInsertId();

  //update event
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE Event SET idCover = ? WHERE idEvent = ?');
  $stmt->execute(array($idImg,$idEvent));
  return $idEvent;
}

function getEventThreads($idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM EventThread WHERE idEvent=?');
  $stmt->execute(array($idEvent));
  return $stmt->fetchAll();
}

function createEventThread($idEvent,$idOwner,$text){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO EventThread VALUES(NULL,?,?,?)');
  $stmt->execute(array($idEvent,$idOwner,$text));
}

function searchEventByDate($date, $userId, $limit, $page){
  //if userId is > 0, searches events visible to user and public events. if not, just searches public events
  if($userId){
    $stmt = Database::getInstance()->getConnection()->prepare("SELECT idCover, Event.idEvent, name FROM Event LEFT OUTER JOIN InvitedEvent WHERE julianday(eventDate) = julianday(?) AND (visibility = 1 OR (Event.idEvent = InvitedEvent.idEvent AND InvitedEvent.idUser = ?) OR idOwner = ?) LIMIT ? OFFSET ?");
    $stmt->execute(array($date, $userId, $userId, $limit + 1, ($page - 1) * $limit));
  }
  else {
    $stmt = Database::getInstance()->getConnection()->prepare("SELECT idCover, Event.idEvent, name FROM Event LEFT OUTER JOIN InvitedEvent WHERE julianday(eventDate) = julianday(?) AND (visibility = 1) LIMIT ? OFFSET ?");
    $stmt->execute(array($date, $limit + 1, ($page - 1) * $limit));
  }
  return $stmt->fetchAll();
}

//finds a result containing any word in the text
function createSearchRegex($text){
  $searchArray = explode(' ', $text);
  return '(' . implode('|', $searchArray) . ')';
}

function searchEventBySearchMode($text, $mode, $dateOperator, $userId, $limit, $page){
  //tests if mode and dateOperator are valid, to prevent SQLinjection
  //runs different queries based on mode and dateOperator to prevent duplicated code
  if(in_array($mode, ['description', 'name', 'location', 'type']) && in_array($dateOperator, ['<', '>='])){
    //if userId is > 0, searches events visible to user and public events. if not, just searches public events
    if($userId){
      $stmt = Database::getInstance()->getConnection()->prepare("SELECT DISTINCT idCover, Event.idEvent, name FROM Event LEFT OUTER JOIN InvitedEvent WHERE (" . $mode . " REGEXP ?) AND julianday(eventDate) " . $dateOperator . " julianday('now') AND (visibility = 1 OR (Event.idEvent = InvitedEvent.idEvent AND InvitedEvent.idUser = ?) OR idOwner = ?) LIMIT ? OFFSET ?");
      $stmt->execute(array(createSearchRegex($text), $userId, $userId, $limit + 1, ($page - 1) * 10));
    }
    else {
      $stmt = Database::getInstance()->getConnection()->prepare("SELECT DISTINCT idCover, Event.idEvent, name FROM Event LEFT OUTER JOIN InvitedEvent WHERE (" . $mode . " REGEXP ?) AND julianday(eventDate) " . $dateOperator . " julianday('now') AND (visibility = 1) LIMIT ? OFFSET ?");
      $stmt->execute(array(createSearchRegex($text), $limit + 1, ($page - 1) * 10));
    }
    return $stmt->fetchAll();
  }
}

function deleteEventCover($id){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idCover FROM Event WHERE idEvent = ?');
  $stmt->execute(array($id));
  $eventCover = $stmt->fetchColumn();
  unlink('images/covers/' . $eventCover);
  unlink('images/icons/' . $eventCover);
  unlink('images/originals/' . $eventCover);
  $stmt = Database::getInstance()->getConnection()->prepare('DELETE FROM Image WHERE idImage = ?');
  $stmt->execute(array($eventCover));
}

function deleteEvent($id){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idImage FROM Image WHERE idEvent = ? OR idAlbum IN (SELECT Album.idAlbum FROM Album WHERE Album.idEvent = ?)');
  $stmt->execute(array($id, $id));
  $results = $stmt->fetchAll();

  deleteEventCover($id);

  foreach ($results as $result) {
    unlink('images/covers/' . $result['idImage']);
    unlink('images/icons/' . $result['idImage']);
  }


  $stmt = Database::getInstance()->getConnection()->prepare('DELETE FROM Event WHERE idEvent = ?');
  $stmt->execute(array($id));
}

function getThreadComments($idThread){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM EventThreadComment WHERE idEventThread = ?');
  $stmt->execute(array($idThread));
  return $stmt->fetchAll();
}

function getThread($idThread){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM EventThread WHERE idEventThread = ?');
  $stmt->execute(array($idThread));
  return $stmt->fetch();
}

function createThreadComment($idThread,$idOwner,$commentText){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO EventThreadComment VALUES(NULL,?,?,?)');
  $stmt->execute(array($idThread,$idOwner,$commentText));
}

function createInvitedEvent($idUser, $idEvent, $intention){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO InvitedEvent VALUES(NULL,?,?,0)');
  $stmt->execute(array($idUser, $idEvent, $intention));
}
function updateInvitedEvent($idIE,$intention){
  $stmt = Database::getInstance()->getConnection()->prepare('UPDATE InvitedEvent SET intention=? WHERE idIE=?');
  $stmt->execute(array($intention, $idIE));
}

function getInvitedEvent($idUser, $idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM InvitedEvent WHERE idUser = ? AND idEvent = ?');
  $stmt->execute(array($idUser, $idEvent));
  return $stmt->fetch();
}

function getIntention1Event($idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT Count(4) FROM InvitedEvent WHERE idEvent = ? AND intention = 1');
  $stmt->execute(array($idEvent));
  return $stmt->fetchColumn();
}

function getIntention2Event($idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT Count(4) FROM InvitedEvent WHERE idEvent = ? AND intention = 2');
  $stmt->execute(array($idEvent));
  return $stmt->fetchColumn();
}

function getPhotos($idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idImage FROM Image WHERE idEvent = ? AND idAlbum IS NULL');
  $stmt->execute(array($idEvent));
  return $stmt->fetchAll();
}

function getAlbums($idEvent){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idAlbum, title FROM Album WHERE idEvent = ?');
  $stmt->execute(array($idEvent));
  return $stmt->fetchAll();
}

function getPhotosByAlbum($idAlbum){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT idImage FROM Image WHERE idAlbum = ?');
  $stmt->execute(array($idAlbum));
  return $stmt->fetchAll();
}
?>
