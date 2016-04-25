<?php
    function createUser($nome, $username, $password, $email, $pais){
        global $conn;
        $query = "INSERT INTO Utilizador(nome, username, password, email, idPais) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nome, $username, $password, $email, $pais]);
    }

    function isLoginCorrect($username, $password) {
        global $conn;
        $query = "SELECT * FROM Utilizador WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($username, sha1($password)));
        return $stmt->fetch() == true;
    }
    
    function accountAlreadyExists($username) {
        global $conn;
        $query = "SELECT username FROM Utilizador WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch() == true;
    }
?>
