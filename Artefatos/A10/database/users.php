<?php
  function createUser($name, $username, $email, $password, $photo, $country) {
    global $conn;
    $query = "INSERT INTO Utilizador VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($name, $username, $email, sha1($password), $photo, $country));
  }

  function isLoginCorrect($username, $password) {
    global $conn;
    $query = "SELECT * FROM Utilizador WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($username, sha1($password)));
    return $stmt->fetch() == true;
  }
?>
