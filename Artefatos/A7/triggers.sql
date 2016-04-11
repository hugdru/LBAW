-- Prevent Rating if check_event_complete is false
DROP TRIGGER IF EXISTS CanRate ON Participacao;

CREATE OR REPLACE FUNCTION trigger_canRate() RETURNS TRIGGER AS $$
BEGIN
  IF NOT check_event_complete(New.eventId) THEN
    RAISE EXCEPTION 'Event is not complete yet';
  END IF;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanRate BEFORE INSERT ON Participacao EXECUTE PROCEDURE trigger_canRate();
