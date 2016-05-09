<?php
    function createUser($nome, $username, $password, $email, $pais, $foto){
        global $conn;
        $query = "INSERT INTO Utilizador(nome, username, password, email, idPais, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nome, $username, sha1($password), $email, $pais, $foto]);
    }

    function isLoginCorrect($username, $password) {
        global $conn;
        $query = "SELECT * FROM Utilizador WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username, sha1($password)]);
        
        $result = $stmt->fetch(PDO::FETCH_OBJ);    
        if($result){
            $_SESSION["avatar"] = $result->foto;
            return true;
        }
        return false;
    }
    
    function accountAlreadyExists($username) {
        global $conn;
        $query = "SELECT username FROM Utilizador WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch() == true;
    }
?>
