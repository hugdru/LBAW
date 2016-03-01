<?
	include_once('PasswordHash.php');
	include_once('database/images.php');
	$saltInCode = 'LTWSalt';

	function validateUser($username, $pw, $maxLoginAttempts, $loginBlockTimeout){
		//retrieve $saltInCode global value
		global $saltInCode;

		$stmt = Database::getInstance()->getConnection()->prepare('SELECT password FROM User WHERE username = ?');
		$stmt->execute(array($username));
		$result = $stmt->fetchColumn();
		//1 -> user logged in
		//-1 -> username doesn't exist
		//-2 -> invalid password
		//-3 -> no more loginAttempts
		if($result){
			$stmt = Database::getInstance()->getConnection()->prepare("SELECT loginAttempts, strftime('%s','now') - strftime('%s',lastLoginAttempt) AS secondsPassed FROM User WHERE username = ?");
			$stmt->execute(array($username));

			//if loginTimeoutBlock has not been exceded and loginAttempts have, return result array, to display how many seconds are left until account is unblocked
			$resultBlock = $stmt->fetch();
			if($resultBlock['loginAttempts'] >= $maxLoginAttempts && $resultBlock['secondsPassed'] < $loginBlockTimeout)
				return $resultBlock;

			if(validate_password($saltInCode . $pw, $result)){
				$stmt = Database::getInstance()->getConnection()->prepare("UPDATE User SET loginAttempts = 0, lastLoginAttempt = datetime('now') WHERE username = ?");
				$stmt->execute(array($username));
				return 1;
			}
			$stmt = Database::getInstance()->getConnection()->prepare("UPDATE User SET loginAttempts = ?, lastLoginAttempt = datetime('now') WHERE username = ?");
			$stmt->execute(array($resultBlock['loginAttempts'] + 1, $username));
			return -2;
		}
		return -1;
	}

	function createUser($name,$username, $pw){
		global $saltInCode;
		$stmt = Database::getInstance()->getConnection()->prepare("INSERT INTO User VALUES(NULL,?,?,?,?,0, datetime('now'), ?)");
		$stmt->execute(array(0,$name,$username, create_hash($saltInCode . $pw),''));
	}

	function changeUserPassword($username, $oldPw, $newPw){
		global $saltInCode;
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT password FROM User WHERE username = ?');
		$stmt->execute(array($username));
		$result = $stmt->fetchColumn();
		if(validate_password($saltInCode . $oldPw, $result)){
			$stmt = Database::getInstance()->getConnection()->prepare('UPDATE User SET password = ? WHERE username = ?');
			return $stmt->execute(array(create_hash($saltInCode . $newPw), $username));
		}
		return false;
	}

	function usernameExists($username){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM User WHERE username = ?');
		$stmt->execute(array($username));
		return $stmt->fetch()?true:false;
	}

	function updateUserDescription($username, $description){
		$stmt = Database::getInstance()->getConnection()->prepare('UPDATE User SET description = ? WHERE username = ?');
		$stmt->execute(array($description, $username));
	}

	function getUserEventsCreated($userId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT Event.idEvent, name, idCover FROM Event WHERE idOwner = ? LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserEventsAttended($userId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT Event.idEvent, name, idCover FROM Event, InvitedEvent WHERE InvitedEvent.idEvent = Event.idEvent AND julianday(\'now\') > julianday(eventDate) AND InvitedEvent.idUser = ? AND intention = 1 LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserEventsAttending($userId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT Event.idEvent, name, idCover, intention FROM Event, InvitedEvent WHERE InvitedEvent.idEvent = Event.idEvent AND julianday(\'now\') < julianday(eventDate) AND InvitedEvent.idUser = ? AND (intention = 1 OR intention = 2) LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserVisibleEventsCreated($userId, $viewerId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT DISTINCT Event.idEvent, name, idCover FROM Event LEFT OUTER JOIN InvitedEvent WHERE idOwner = ? AND (visibility = 1 OR (InvitedEvent.idEvent = Event.idEvent AND InvitedEvent.idUser = ?)) LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $viewerId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserVisibleEventsAttended($userId, $viewerId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT DISTINCT Event.idEvent, name, idCover FROM Event, InvitedEvent i1 LEFT OUTER JOIN InvitedEvent i2 WHERE i1.idEvent = Event.idEvent AND julianday(\'now\') > julianday(eventDate) AND i1.idUser = ? AND i1.intention = 1 AND (visibility = 1 OR (i2.idEvent = Event.idEvent AND i2.idUser = ?)) LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $viewerId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserVisibleEventsAttending($userId, $viewerId, $limit, $offset){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT DISTINCT Event.idEvent, name, idCover, i1.intention FROM Event, InvitedEvent i1 LEFT OUTER JOIN InvitedEvent i2 WHERE i1.idEvent = Event.idEvent AND julianday(\'now\') < julianday(eventDate) AND i1.idUser = ? AND (i1.intention = 1 OR i1.intention = 2)  AND (visibility = 1 OR (i2.idEvent = Event.idEvent AND i2.idUser = ?)) LIMIT ? OFFSET ?');
		$stmt->execute(array($userId, $viewerId, $limit, $offset));
		return $stmt->fetchAll();
	}

	function getUserEventsInvited($userId){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT Event.idEvent, Event.name, Event.idCover FROM Event, InvitedEvent WHERE Event.idEvent=InvitedEvent.idEvent AND InvitedEvent.idUser=? AND InvitedEvent.intention=?');
		$stmt->execute(array($userId,0));
		return $stmt->fetchAll();
	}
	function getEvents($user, &$createdEvents, &$attendedEvents, &$attendingEvents, $limit, $offset){
	  if($_SESSION['userId'] == $user['idUser']){
	    $createdEvents = getUserEventsCreated($user['idUser'], $limit, $offset);
	    $attendedEvents = getUserEventsAttended($user['idUser'], $limit, $offset);
	    $attendingEvents = getUserEventsAttending($user['idUser'], $limit, $offset);
	  }
	  else{
	    $createdEvents = getUserVisibleEventsCreated($user['idUser'], $_SESSION['userId'], $limit, $offset);
	    $attendedEvents = getUserVisibleEventsAttended($user['idUser'], $_SESSION['userId'], $limit, $offset);
	    $attendingEvents = getUserVisibleEventsAttending($user['idUser'], $_SESSION['userId'], $limit, $offset);
	  }
	}

	function getUserId($username){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT idUser FROM User WHERE username = ?');
		$stmt->execute(array($username));
		return $stmt->fetchColumn();
	}

	function getUserById($userId){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT * FROM User WHERE idUser = ?');
		$stmt->execute(array($userId));
		return $stmt->fetch();
	}

	function getUserNameById($userId){
		$stmt = Database::getInstance()->getConnection()->prepare('SELECT name FROM User WHERE idUser = ?');
		$stmt->execute(array($userId));
		return $stmt->fetchColumn();
	}

	function updateProfilePic($userId,$imgDesc){
	$idImg = createImage($imgDesc);
	$stmt = Database::getInstance()->getConnection()->prepare('UPDATE User SET idProfPic=? WHERE idUser=?');
	$stmt->execute(array($idImg,$userId));
	}
?>
