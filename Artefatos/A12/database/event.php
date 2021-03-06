<?php

function insertEvent($titulo, $capa, $descricao, $localizacao, $dataInicio, $duracao, $publico, $idUtilizador, $base)
{
    global $conn;
    $conn->beginTransaction();
    $stmt = $conn->prepare("INSERT INTO Evento(titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute(array($titulo, $capa, $descricao, $localizacao, $dataInicio, $duracao, $publico)) === false) {
        return false;
    }
    $newID = $conn->lastInsertId("evento_idevento_seq");

    $stmt = $conn->prepare("INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (?, ?)");
    if ($stmt->execute(array($newID, $idUtilizador)) === false) {
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (?, ?, NULL, NULL)");
    if ($stmt->execute(array($newID, $idUtilizador)) === false) {
        return false;
    }

    $link = $base . "pages/event/view_event.php?id=" . $newID;
    $textoNotif = "One of the users you follow created a new event";

    $stmt = $conn->prepare("INSERT INTO Notificacao(idNotificado, descricao, link, lida, idNotificante)
                            SELECT Seguidor.idSeguidor, ?, ?, FALSE, Seguidor.idSeguido FROM Seguidor
                            WHERE Seguidor.idSeguido = ?");
    if ($stmt->execute(array($textoNotif, $link, $idUtilizador)) === false) {
        return false;
    }
    $conn->commit();
    return $newID;
}

function can_view($idutilizador, $idevento) {
    global $conn;
    $stmt = $conn->prepare("SELECT can_view_event(?, ?)");
    $stmt->execute(array($idutilizador, $idevento));
    $result = $stmt->fetch();
    return $result['can_view_event'];
}

function updateEventPhoto($idevento, $imagePath)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE evento SET capa = ? WHERE idevento = ?");
    return $stmt->execute(array($imagePath, $idevento)) !== false;
}

function getTopEventsPublic()
{
    global $conn;
    $stmt = $conn->prepare("SELECT get_top_events_public()");
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_top_events_public"];
    }
    return false;
}

function getTopEventsAuthenticated($idutilizador)
{
    global $conn;
    $stmt = $conn->prepare("SELECT get_top_events_authenticated(?)");
    $stmt->execute(array($idutilizador));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_top_events_authenticated"];
    }
    return false;
}

function getEventsAuthenticated($idutilizador, $text) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events_authenticated(?, ?)");
    $stmt->execute(array($idutilizador, $text));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events_authenticated"];
    }
    return false;
}

function getEventsSimpleAuthenticated($idutilizador) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events_simple_authenticated(?)");
    $stmt->execute(array($idutilizador));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events_simple_authenticated"];
    }
    return false;
}

function getMyEvents($idutilizador, $texto) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_my_events(?, ?)");
    $stmt->execute(array($idutilizador, $texto));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_my_events"];
    }
    return false;
}

function getMyEventsSimple($idutilizador) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_my_events_simple(?)");
    $stmt->execute(array($idutilizador));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_my_events_simple"];
    }
    return false;
}

function getEventsPublic($texto) {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events_public(?)");
    $stmt->execute(array($texto));
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events_public"];
    }
    return false;
}

function getEventsPublicSimple() {
    global $conn;
    $stmt = $conn->prepare("SELECT get_events_public_simple()");
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result !== false) {
        return $result["get_events_public_simple"];
    }
    return false;
}

function getEventByID($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Evento WHERE idEvento = ?");
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function getCommentsSection($event_id){
    global $conn;
    $query = "SELECT idComentario, texto, dataComentario, idComentador, idComentarioPai, Utilizador.username, Utilizador.foto
	              FROM Comentario
	              JOIN Utilizador ON Utilizador.idUtilizador = Comentario.idComentador
	              WHERE Comentario.idEvento = ?
	              ORDER BY dataComentario DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function getPhotosAlbums($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT Album.idAlbum, Album.nome, Album.descricao, json_agg(json_build_object('id', Imagem.IdImagem, 'caminho', Imagem.caminho, 'data', Imagem.data))
        FROM Album
        JOIN Imagem ON Imagem.idAlbum = Album.idAlbum
        WHERE Album.idEvento = ?
        GROUP BY Album.idAlbum;"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function getEventPoll($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT Sondagem.IdSondagem, Sondagem.descricao, Sondagem.data, json_agg(json_build_object('id', Opcao.idOpcao, 'descricao', Opcao.descricao))
        FROM Sondagem
        JOIN Opcao ON Opcao.idSondagem = Sondagem.idSondagem
        WHERE Sondagem.idEvento = ?
        GROUP BY Sondagem.idSondagem;"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function getPollResults($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT json_agg(ResultsById.ResultsById) AS SondagemResults
        FROM (
          SELECT json_build_object('id', Opcao.idOpcao, 'votes', COUNT(Opcao.idOpcao)) AS ResultsById FROM Opcao
          JOIN UtilizadorOpcao ON Opcao.idOpcao = UtilizadorOpcao.idOpcao
          WHERE Opcao.idSondagem = ?
          GROUP BY Opcao.idOpcao
        ) AS ResultsById;"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function getParticipants($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
        FROM Participacao
        JOIN Utilizador ON Participacao.IdParticipante = Utilizador.idUtilizador
        WHERE Participacao.idEvento = ?;"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function getParticipantsNumber($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT COUNT(idParticipante) AS Participant FROM Participacao
        WHERE idEvento=?;"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchColumn();
}

function getHosts($event_id)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT Utilizador.nome, Utilizador.username FROM Utilizador
        INNER JOIN Anfitriao
        ON Utilizador.idUtilizador = Anfitriao.idAnfitriao
        WHERE Anfitriao.idEvento = ?"
    );
    $stmt->execute(array($event_id));
    return $stmt->fetchAll();
}

function addUserToHosts($idutilizador, $idevento){
    global $conn;
    $query = "INSERT INTO Anfitriao(idevento,idanfitriao) VALUES(?,?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$idevento, $idutilizador]);
    return $stmt->fetch();
}

function insertComment($texto, $idcomentador, $idevento, $base){
    global $conn;

    $conn->beginTransaction();
    $query = "INSERT INTO Comentario(texto, idcomentador, idevento) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt->execute([$texto, $idcomentador, $idevento]) === false) {
        return false;
    }

    $link = $base . "pages/event/view_event.php?id=" . $idevento;
    $textoNotifAnf = "Your event has new comments";

    $stmt = $conn->prepare("INSERT INTO Notificacao(idNotificado, descricao, link, lida, idNotificante)
                            SELECT Anfitriao.idAnfitriao, ?, ?, FALSE, ?
                            FROM Anfitriao
                            WHERE idEvento = ?
                           ");
    if ($stmt->execute(array($textoNotifAnf, $link, $idcomentador, $idevento)) === false) {
        return false;
    }
    $conn->commit();

    return true;
}

function deleteEvent($idevento)
{
    global $conn;

    $query = "DELETE FROM Evento WHERE idEvento = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$idevento]);
    return $stmt->fetch() !== false;
}

function insertParticipation($idevento, $idutilizador){
    global $conn;
    $query = "INSERT INTO Participacao(IdEvento, IdParticipante, classificacao, comentario) VALUES (?, ?, NULL, NULL)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$idevento, $idutilizador]);
    return $stmt->fetch() !== false;
}

?>