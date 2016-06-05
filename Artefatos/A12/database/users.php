<?php
function insertUser($nome, $username, $password, $email, $pais, $foto)
{
    global $conn;
    $query = "INSERT INTO Utilizador(nome, username, password, email, idPais, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$nome, $username, $password, $email, $pais, $foto]);
}

function getUserByUsername($username)
{
    global $conn;
    $query = "SELECT * FROM Utilizador WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function getUserById($idutilizador)
{
    global $conn;
    $query = "SELECT * FROM Utilizador WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$idutilizador]);
    return $stmt->fetch();
}

function usernameRegistered($username)
{
    global $conn;
    $query = "SELECT idutilizador FROM Utilizador WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);
    return $stmt->fetch() == true;
}

function checkIfUsernameExists($username)
{
    global $conn;
    $query = "SELECT username FROM Utilizador WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);
    return $stmt->fetch() == true;
}


function checkIfEmailExists($email)
{
    global $conn;
    $query = "SELECT email FROM Utilizador WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$email]);
    return $stmt->fetch() == true;
}

function updatePassword($idutilizador, $password)
{
    global $conn;
    $query = "UPDATE utilizador SET password = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$password, $idutilizador]);
}

function getPhoto($id)
{
    global $conn;
    $query = "SELECT foto FROM Utilizador WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch() == true;
}

function getCountryList(){
    global $conn;

    $query = "SELECT idpais,nome FROM pais ORDER BY idpais ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}        
?>
