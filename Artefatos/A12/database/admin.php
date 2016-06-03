<?php
    //Admin Database functions

    // Set Hash Function Here
    function adminHash($text){
        return $text;
    }

    function authenticate($username, $password) {
        global $conn;
        
        $query = "SELECT * FROM administrador WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($username, adminHash($password)));
        return $stmt->fetch() == true;
    }
    
    function getAdminData(){
        global $conn;
        
        $admin_username = $_SESSION["admin_username"];
        $admin_password = $_SESSION["admin_password"];
        
        $query = "SELECT username,email FROM administrador WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($admin_username, $admin_password));
        return $stmt->fetch();
    }
    
    function getUserList(){
        global $conn;
        
        $query = "SELECT idutilizador,nome,username,email FROM utilizador ORDER BY idutilizador DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function getCommentList(){
        global $conn;
        
        $query = "SELECT idcomentario, username, texto, comentario.idevento, titulo, idcomentariopai FROM comentario, utilizador, evento WHERE comentario.idcomentador = utilizador.idutilizador AND comentario.idevento = evento.idevento ORDER BY idcomentario DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function getEventList(){
        global $conn;
        
        $query = "SELECT idevento,titulo,descricao,publico FROM evento ORDER BY idevento DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function getAdminList(){
        global $conn;
        
        $query = "SELECT idadministrador,username,email FROM administrador ORDER BY idadministrador DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function removeUser($id){
        global $conn;
        
        $query = "DELETE FROM utilizador WHERE idutilizador = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
    }
    
    function removeEvent($id){
        global $conn;
        
        $query = "DELETE FROM evento WHERE idevento = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
    }
    
    function removeComment($id){
        global $conn;
        
        $query = "DELETE FROM comentario WHERE idcomentario = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
    }
    
    function createAdmin($username, $email, $password, $password_r){
        global $conn;
        
        if ($password!=$password_r){
            $_SESSION['error_messages'][] = 
                    '<strong>Create Admin Account Failed:</strong> Password and Repeated Password do not match';
            return;
        }
        
        if (strlen($password) < 8){
            $_SESSION['error_messages'][] = 
                    '<strong>Create Admin Account Failed:</strong> Passwords must be atleast 8 characters long';
            return;
        }
        
        // Check if Unique Keys Already Exist
        $query = "select idadministrador FROM administrador WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        if($stmt->fetch() == true){
            $_SESSION['error_messages'][] = 
                    '<strong>Create Admin Account Failed:</strong> Username Already in Use';
            return;
        }
        
        $query = "select idadministrador FROM administrador WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        if($stmt->fetch() == true){
            $_SESSION['error_messages'][] = 
                    '<strong>Create Admin Account Failed:</strong> Email Already in Use';
            return;
        }
        
        try{
            // Register Account
            $query = "INSERT INTO Administrador(username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$username, $email, adminHash($password)]);
            $_SESSION['success_messages'][] = 
                        '<strong>Created Admin Account Sucessfully</strong>';
        }catch(Exception $ex){
            $_SESSION['error_messages'][] = 
                    '<strong>Create Admin Account Failed:</strong> Malformed Email Address';            
        }
        
    }
?>

