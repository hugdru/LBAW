<?php
function insertUser($nome, $username, $password, $email, $pais, $foto)
{
    global $conn;
    $query = "INSERT INTO Utilizador(nome, username, password, email, idPais, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt->execute([$nome, $username, $password, $email, $pais, $foto]) === false) {
        return false;
    } else {
        return $conn->lastInsertId("utilizador_idutilizador_seq");
    }
}

function updateUserPhoto($idutilizador, $imagePath) {
    global $conn;
    $update = "UPDATE utilizador SET foto = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($update);
    return $stmt->execute(array($imagePath, $idutilizador)) !== false;
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

function getCountryList()
{
    global $conn;
    $query = "SELECT idpais, nome FROM pais ORDER BY idpais ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCountryById($idPais){
    global $conn;
    $query = "SELECT nome FROM pais WHERE idpais = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$idPais]);
    $row = $stmt->fetch();
    if ($row){
        return $row["nome"];
    }
    else
        return false;
}

function updatePhoto($idutilizador, $foto){
    global $conn;
    $query = "UPDATE utilizador SET foto = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$foto, $idutilizador]);
}

function updateDescription($idutilizador, $descricao){
    global $conn;
    $query = "UPDATE utilizador SET descricao = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$descricao, $idutilizador]);
}


function updateEmail($idutilizador, $email){
    global $conn;
    $query = "UPDATE utilizador SET email = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$email, $idutilizador]);
}

function updateCountry($idutilizador, $idpais){
    global $conn;
    $query = "UPDATE utilizador SET idpais = ? WHERE idutilizador = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$idpais, $idutilizador]);
}

?>
