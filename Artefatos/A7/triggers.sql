-- Prevent Rating if user did not particapte or the event is not completed
DROP TRIGGER IF EXISTS CanRate ON Participacao;

CREATE OR REPLACE FUNCTION trigger_canRate() RETURNS TRIGGER AS $$
BEGIN
	IF NOT check_participation(New.idParticipante) THEN
    	RAISE EXCEPTION 'Current user did not participate in the event';
	END IF;
	IF NOT check_event_complete(New.idEvento) THEN
		RAISE EXCEPTION 'Event is not complete yet';
	END IF;
	RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanRate BEFORE INSERT ON Participacao 
FOR EACH ROW
EXECUTE PROCEDURE trigger_canRate();


-- Delete every image of an album after its deletion
CREATE OR REPLACE FUNCTION trigger_deleteImages() RETURNS TRIGGER AS $$
BEGIN
	DELETE FROM Imagem
	WHERE Imagem.idAlbum = Album.TG_RELID;
	RETORN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER DeleteImages BEFORE DELETE ON Album
FOR EACH ROW
EXECUTE PROCEDURE trigger_deleteImages();


-- Prevent Users from following themselves
CREATE OR REPLACE FUNCTION trigger_canFollow() RETURNS TRIGGER AS $$
BEGIN
	IF (New.IdSeguidor = New.IdSeguido) THEN
    	RAISE EXCEPTION 'Users cannot follow themselves';
	END IF;
	RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanFollow BEFORE INSERT ON Seguidor
FOR EACH ROW
EXECUTE PROCEDURE trigger_canFollow();