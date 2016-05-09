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
    
    //Error Codes: -1 New Passwords mismatch, -2 Authentication failure, 1 Sucess
    function updatePassword($username, $original_password, $new_password, $confirm_new_password){
        // Check if new == confirm
        if($new_password != $confirm_new_password){
            return -1;
        }
        
        // Check if user credentials verify
        if(! isLoginCorrect($username, $original_password)){
            return -2;
        }
        
        // Proceed with changes
        global $conn;
        $query = "UPDATE utilizador SET password=? where username=?";
        $stmt = $conn->prepare($query);
        $stmt->execute([sha1($new_password), $username]);
        return 1;
    }
?>
