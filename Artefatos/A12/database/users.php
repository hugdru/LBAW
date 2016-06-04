<?php
function insertUser($nome, $username, $password, $email, $pais, $foto)
{
    global $conn;
    $query = "INSERT INTO Utilizador(nome, username, password, email, idPais, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$nome, $username, $password, $email, $pais, $foto]);
}

function getUserByUsername($username)
{
    global $conn;
    $query = "SELECT * FROM Utilizador WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);

    return $stmt->fetch(PDO::FETCH_OBJ);
}

function getUserById($id)
{
    global $conn;
    $query = "SELECT * FROM Utilizador WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_OBJ);
}

function usernameRegistered($username)
{
    global $conn;
    $query = "SELECT idUtilizador FROM Utilizador WHERE username = ?";
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

function updatePassword($id, $password)
{
    global $conn;
    $query = "UPDATE utilizador SET password=? where id=?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$password, $id]);
    return 1;
}

function getPhoto($id)
{
    global $conn;
    $query = "SELECT foto FROM Utilizador WHERE idUtilizador = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch() == true;
}

?>
