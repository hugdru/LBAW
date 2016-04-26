<?php
    //Admin Database functions

    function authenticate($username, $password) {
        global $conn;
        
        $query = "SELECT * FROM administrador WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($username, $password));
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
    
    function removeUser($id){
        global $conn;
        
        $query = "DELETE FROM utilizador WHERE idutilizador = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
    }
?>

