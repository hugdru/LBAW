-- Check if event is completed
CREATE OR REPLACE FUNCTION check_event_complete(INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT idEvento, dataInicio, duracao FROM Evento WHERE idEvento = $1 AND dataInicio + (duracao * INTERVAL '1 second') >= CURRENT_TIMESTAMP
  );
$$ LANGUAGE SQL;

