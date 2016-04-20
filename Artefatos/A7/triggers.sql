-- Prevent Rating if user did not participate or the event is not completed
DROP TRIGGER IF EXISTS can_rate ON Participacao;
DROP TRIGGER IF EXISTS can_participate ON Participacao;

CREATE OR REPLACE FUNCTION check_insert_participation() RETURNS TRIGGER AS $$
BEGIN
  IF (New.classificacao != NULL || New.comentario != NULL) THEN
    RAISE EXCEPTION 'Only previously existing participants can vote';
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION check_can_rate() RETURNS TRIGGER AS $$
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

CREATE TRIGGER can_rate BEFORE INSERT ON Participacao
FOR EACH ROW
EXECUTE PROCEDURE check_insert_participation();

CREATE TRIGGER can_participate BEFORE UPDATE ON Participacao
FOR EACH ROW
EXECUTE PROCEDURE check_can_rate();

-- Prevent Users from following themselves
DROP TRIGGER IF EXISTS CanFollow ON Seguidor;

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
DROP TRIGGER IF EXISTS CanVote ON ComentarioVoto;

CREATE OR REPLACE FUNCTION trigger_canVote() RETURNS TRIGGER AS $$
BEGIN
  IF NOT check_vote(New.IdComentario, New.IdVotante) THEN
    RAISE EXCEPTION 'Users cannot vote on their own comments';
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanVote BEFORE INSERT ON ComentarioVoto
FOR EACH ROW
EXECUTE PROCEDURE trigger_canVote();
