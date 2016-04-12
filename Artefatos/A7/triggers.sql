-- Prevent Rating if user did not participate or the event is not completed
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
  RETURN OLD;
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


-- Prevent Users from voting on their own comments
CREATE OR REPLACE FUNCTION trigger_canVote() RETURNS TRIGGER AS $$
BEGIN
  IF NOT check_vote(New.IdComentario, New.IdVotante) THEN
    RAISE EXCEPTION 'Users cannote vote on their own comments';
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanVote BEFORE INSERT ON ComentarioVoto
FOR EACH ROW
EXECUTE PROCEDURE trigger_canVote();


-- Delete every option of a poll after its deletion
CREATE OR REPLACE FUNCTION trigger_deleteOptions() RETURNS TRIGGER AS $$
BEGIN
  DELETE FROM Opcao
  WHERE Opcao.IdSonsagem = Sondagem.TG_RELID;
  RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER DeleteOptions BEFORE DELETE ON Sondagem
FOR EACH ROW
EXECUTE PROCEDURE trigger_deleteOptions();