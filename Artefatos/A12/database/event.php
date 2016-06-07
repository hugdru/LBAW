<?php
function insertEvent($titulo, $capa, $descricao, $localizacao, $dataInicio, $duracao, $publico)
{
    global $conn;
    $insert = "INSERT INTO Evento(titulo, capa, descricao, localizacao, datainicio, duracao, publico) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    if ($stmt->execute(array($titulo, $capa, $descricao, $localizacao, $dataInicio, $duracao, $publico)) === false) {
        return false;
    } else {
        return $conn->lastInsertId("evento_idevento_seq");
    }
}

function updateEventPhoto($idevento, $imagePath) {
    global $conn;
    $update = "UPDATE evento SET capa = ? WHERE idevento = ?";
    $stmt = $conn->prepare($update);
    return $stmt->execute(array($imagePath, $idevento)) !== false;
}

function getEvents($texto) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events(?)");
    $stmt->execute(array($texto));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events"];
    }
    return false;
}

function getEventsSimple() {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events_simple()");
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events_simple"];
    }
    return false;
}
?>