DROP SCHEMA IF EXISTS final CASCADE;
CREATE SCHEMA final;
SET SCHEMA 'final';

-- CREATE TABLE
DROP TABLE IF EXISTS Evento CASCADE;
DROP TABLE IF EXISTS Utilizador CASCADE;
DROP TABLE IF EXISTS Sondagem CASCADE;
DROP TABLE IF EXISTS Opcao CASCADE;
DROP TABLE IF EXISTS Comentario CASCADE;
DROP TABLE IF EXISTS Pais CASCADE;
DROP TABLE IF EXISTS Seguidor CASCADE;
DROP TABLE IF EXISTS Notificacao CASCADE;
DROP TABLE IF EXISTS Participacao CASCADE;
DROP TABLE IF EXISTS Convite CASCADE;
DROP TABLE IF EXISTS Anfitriao CASCADE;
DROP TABLE IF EXISTS UtilizadorOpcao CASCADE;
DROP TABLE IF EXISTS ComentarioVoto CASCADE;
DROP TABLE IF EXISTS Administrador CASCADE;
DROP TABLE IF EXISTS Album CASCADE;
DROP TABLE IF EXISTS Imagem CASCADE;

DROP DOMAIN IF EXISTS DMClassificacao;

CREATE DOMAIN DMClassificacao AS Integer CHECK( VALUE <= 5 AND VALUE >= 0);

CREATE TABLE Evento (
  idEvento SERIAL PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  capa TEXT,
  descricao TEXT,
  localizacao TEXT NOT NULL,
  dataInicio TIMESTAMP NOT NULL CHECK (dataInicio >= CURRENT_TIMESTAMP),
  duracao INTEGER NOT NULL,
  publico BOOLEAN DEFAULT TRUE NOT NULL
);

CREATE TABLE Pais(
  idPais SERIAL PRIMARY KEY,
  nome TEXT NOT NULL
);

CREATE TABLE Utilizador(
  idUtilizador SERIAL PRIMARY KEY,
  nome TEXT NOT NULL,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL CHECK( LENGTH(password) >= 8),
  foto TEXT,
  email VARCHAR(100) UNIQUE NOT NULL CHECK ( email ~* '^[^\s@]+@[^\s@]+\.[^\s@.]+$'),
  idPais INTEGER NOT NULL REFERENCES Pais(idPais) ON DELETE SET NULL
);

CREATE TABLE Sondagem(
  idSondagem SERIAL PRIMARY KEY,
  descricao TEXT,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  escolhaMultipla BOOLEAN DEFAULT FALSE NOT NULL,
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento) ON DELETE CASCADE
);

CREATE TABLE Opcao(
  idOpcao SERIAL PRIMARY KEY,
  descricao TEXT NOT NULL,
  idSondagem INTEGER NOT NULL REFERENCES Sondagem(idSondagem) ON DELETE CASCADE
);

CREATE TABLE Comentario(
  idComentario SERIAL PRIMARY KEY,
  texto TEXT NOT NULL,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  idComentador INTEGER NOT NULL REFERENCES Utilizador(idUtilizador) ON DELETE CASCADE,
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento) ON DELETE CASCADE,
  idComentarioPai INTEGER REFERENCES Comentario(idComentario) ON DELETE CASCADE
);

CREATE TABLE Seguidor(
  idSeguidor INTEGER REFERENCES Utilizador(idUtilizador),
  idSeguido INTEGER REFERENCES Utilizador(idUtilizador),
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  PRIMARY KEY(idSeguidor, idSeguido)
);

CREATE TABLE Notificacao(
  idNotificacao SERIAL PRIMARY KEY,
  idNotificado INTEGER NOT NULL REFERENCES Utilizador(idUtilizador),
  descricao TEXT NOT NULL,
  link TEXT NOT NULL,
  lida BOOLEAN NOT NULL,
  idNotificante INTEGER NOT NULL REFERENCES Utilizador(idUtilizador)
);

CREATE TABLE Participacao(
  idEvento INTEGER REFERENCES Evento(idEvento) ON DELETE CASCADE,
  idParticipante INTEGER REFERENCES Utilizador(idUtilizador) ON DELETE CASCADE,
  classificacao DMClassificacao,
  comentario TEXT,
  PRIMARY KEY(idEvento, idParticipante)
);

CREATE TABLE Convite(
  idEvento INTEGER REFERENCES Evento(idEvento) ON DELETE CASCADE,
  idConvidado INTEGER REFERENCES Utilizador(idUtilizador) ON DELETE CASCADE,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  resposta BOOLEAN,
  PRIMARY KEY(idEvento, idConvidado)
);

CREATE TABLE Anfitriao(
  idEvento INTEGER REFERENCES Evento(idEvento) ON DELETE CASCADE,
  idAnfitriao INTEGER REFERENCES Utilizador(idUtilizador) ON DELETE CASCADE,
  PRIMARY KEY(idEvento, idAnfitriao)
);

CREATE TABLE UtilizadorOpcao(
  idUtilizador INTEGER REFERENCES Utilizador(idUtilizador) ON DELETE CASCADE,
  idOpcao INTEGER REFERENCES Opcao(idOpcao) ON DELETE CASCADE,
  PRIMARY KEY(idUtilizador, idOpcao)
);

CREATE TABLE ComentarioVoto(
  idComentario INTEGER REFERENCES Comentario(idComentario) ON DELETE CASCADE,
  idVotante INTEGER REFERENCES Utilizador(idUtilizador),
  positivo BOOLEAN NOT NULL,
  PRIMARY KEY(idComentario, idVotante)
);

CREATE TABLE Administrador(
  idAdministrador SERIAL PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL CHECK( LENGTH(password) >= 8),
  email VARCHAR(100) UNIQUE NOT NULL CHECK ( email ~* '^[^\s@]+@[^\s@]+\.[^\s@.]+$')
);

CREATE TABLE Album(
  idAlbum SERIAL PRIMARY KEY,
  nome TEXT NOT NULL,
  descricao TEXT,
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento) ON DELETE CASCADE
);

CREATE TABLE Imagem(
  idImagem SERIAL PRIMARY KEY,
  caminho TEXT NOT NULL,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  idAlbum INTEGER NOT NULL REFERENCES Album(idAlbum) ON DELETE CASCADE
);
-- END OF CREATE TABLE

-- INDEXES
-- Index for login
CREATE UNIQUE INDEX
utilizador_username_index ON Utilizador(username);

-- Index for login
CREATE UNIQUE INDEX
utilizador_email_index ON Utilizador(email);

-- Full text search indexes for Evento, these are always used together
CREATE INDEX fts_evento_index
ON Evento
USING
gin((
    setweight(to_tsvector('english', titulo),'A') ||
    setweight(to_tsvector('english', descricao), 'B') ||
    setweight(to_tsvector('english', localizacao), 'B')
));
-- END OF INDEXES

-- FUNCTIONS
-- Check if event is completed
CREATE OR REPLACE FUNCTION check_event_complete(INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT idEvento, dataInicio, duracao FROM Evento WHERE idEvento = $1 AND dataInicio + (duracao * INTERVAL '1 second') >= CURRENT_TIMESTAMP
  );
$$ LANGUAGE SQL;

-- Check if user participated in event
CREATE OR REPLACE FUNCTION check_participation(INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT Evento.idEvento, idParticipante FROM Evento, Participacao WHERE Evento.idEvento = Participacao.idParticipante AND Participacao.idParticipante = $1
  );
$$ LANGUAGE SQL;

-- Check if user can vote in the comment
CREATE OR REPLACE FUNCTION check_vote(INTEGER, INTEGER) RETURNS BOOLEAN AS $$
  SELECT EXISTS (
    SELECT IdComentario, IdComentador FROM Comentario WHERE Comentario.IdComentario = $1 AND Comentario.IdComentador = $2
  );
$$ LANGUAGE SQL;
-- END OF FUNCTIONS

-- TRIGGERS
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
-- END OF TRIGGERS
