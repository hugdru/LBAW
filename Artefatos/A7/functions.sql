-- Check if event is completed
CREATE OR REPLACE FUNCTION check_event_complete(INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT idEvento, dataInicio, duracao FROM Evento WHERE idEvento = $1 AND dataInicio + (duracao * INTERVAL '1 second') >= CURRENT_TIMESTAMP
  );
$$ LANGUAGE SQL;

-- Check if user participated in event
CREATE OR REPLACE FUNCTION check_participation(INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT idEvento, idParticipante FROM Evento, Participante WHERE Evento.idEvento = Participante.idParticipante AND Participante.idParticipante = $1
  );
$$ LANGUAGE SQL;

-- Check if user
CREATE OR REPLACE FUNCTION check_participation(INTEGER, INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT IdComentario, IdComentador FROM Comentario WHERE Comentario.IdComentario = $1 AND Comentario.IdComentador = $2
  );
$$ LANGUAGE SQL;