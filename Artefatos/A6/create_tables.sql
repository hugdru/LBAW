DROP TABLE IF EXISTS Evento CASCADE;
DROP TABLE IF EXISTS Utilizador CASCADE;
DROP TABLE IF EXISTS Sondagem CASCADE;
DROP TABLE IF EXISTS Opcao CASCADE;
DROP TABLE IF EXISTS Comentario CASCADE;
DROP TABLE IF EXISTS Pais CASCADE;
DROP TABLE IF EXISTS Seguidor CASCADE;
DROP TABLE IF EXISTS Participacao CASCADE;
DROP TABLE IF EXISTS Convite CASCADE;
DROP TABLE IF EXISTS Anfitriao CASCADE;
DROP TABLE IF EXISTS UtilizadorOpcao CASCADE;
DROP TABLE IF EXISTS SondagemOpcao CASCADE;
DROP TABLE IF EXISTS ComentarioVoto CASCADE;
DROP TABLE IF EXISTS Administrador CASCADE;
DROP TABLE IF EXISTS Album CASCADE;
DROP TABLE IF EXISTS Imagem CASCADE;

DROP DOMAIN IF EXISTS Classificacao;

CREATE DOMAIN DMClassificacao AS Integer CHECK( VALUE <= 5 AND VALUE >= 0);

-- Tomar atencao as relacoes, as referencias so podem ser criadas depois do referendo

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
  email VARCHAR(100) UNIQUE NOT NULL CHECK ( email ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$' ),
  idPais INTEGER NOT NULL REFERENCES Pais(idPais)
);

CREATE TABLE Sondagem(
  idSondagem SERIAL PRIMARY KEY,
  descricao TEXT,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  escolhaMultipla BOOLEAN DEFAULT FALSE NOT NULL,
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento)
);

CREATE TABLE Opcao(
  idOpcao SERIAL PRIMARY KEY,
  descricao TEXT NOT NULL,
  idSondagem INTEGER NOT NULL REFERENCES Sondagem(idSondagem)
);

CREATE TABLE Comentario(
  idComentario SERIAL PRIMARY KEY,
  texto TEXT NOT NULL,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  idComentador INTEGER NOT NULL REFERENCES Utilizador(idUtilizador),
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento),
  idComentarioPai INTEGER REFERENCES Comentario(idComentario)
);



-- Constrain -> idSeguidor != idSeguido
CREATE TABLE Seguidor(
  idSeguidor INTEGER REFERENCES Utilizador(idUtilizador),
  idSeguido INTEGER REFERENCES Utilizador(idUtilizador),
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  PRIMARY KEY(idSeguidor, idSeguido)
);

-- Constrain -> range da classificacao
CREATE TABLE Participacao(
  idEvento INTEGER REFERENCES Evento(idEvento),
  idParticipante INTEGER REFERENCES Utilizador(idUtilizador),
  classificacao DMClassificacao,
  comentario TEXT,
  PRIMARY KEY(idEvento, idParticipante)
);

CREATE TABLE Convite(
  idEvento INTEGER REFERENCES Evento(idEvento),
  idConvidado INTEGER REFERENCES Utilizador(idUtilizador),
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  resposta BOOLEAN,
  PRIMARY KEY(idEvento, idConvidado)
);

CREATE TABLE Anfitriao(
  idEvento INTEGER REFERENCES Evento(idEvento),
  idAnfitriao INTEGER REFERENCES Utilizador(idUtilizador),
  PRIMARY KEY(idEvento, idAnfitriao)
);

CREATE TABLE UtilizadorOpcao(
  idUtilizador INTEGER REFERENCES Utilizador(idUtilizador),
  idOpcao INTEGER REFERENCES Opcao(idOpcao),
  PRIMARY KEY(idUtilizador, idOpcao)
);

CREATE TABLE SondagemOpcao(
  idSondagem INTEGER REFERENCES Sondagem(idSondagem),
  idOpcao INTEGER REFERENCES Opcao(idOpcao),
  PRIMARY KEY(idSondagem, idOpcao)
);

CREATE TABLE ComentarioVoto(
  idComentario INTEGER REFERENCES Comentario(idComentario),
  idVotante INTEGER REFERENCES Utilizador(idUtilizador),
  positivo BOOLEAN NOT NULL,
  PRIMARY KEY(idComentario, idVotante)
);

CREATE TABLE Administrador(
  idAdministrador SERIAL PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL CHECK( LENGTH(password) >= 8),
  email VARCHAR(100) UNIQUE NOT NULL CHECK ( email ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$' )
);

CREATE TABLE Album(
  idAlbum SERIAL PRIMARY KEY,
  nome TEXT NOT NULL,
  descricao TEXT,
  idEvento INTEGER NOT NULL REFERENCES Evento(idEvento)
);

CREATE TABLE Imagem(
  idImagem SERIAL PRIMARY KEY,
  caminho TEXT NOT NULL,
  "data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),
  idAlbum INTEGER NOT NULL REFERENCES Album(idAlbum)
);

-- User Defined Functions

-- Check if event is completed
CREATE OR REPLACE FUNCTION check_event_complete(INTEGER) RETURNS BOOLEAN AS $$ 
BEGIN
  SELECT EXISTS (
    SELECT idEvento, dataInicio, duracao FROM Evento WHERE idEvento = $1 AND dataInicio + (duracao * INTERVAL '1 second') >= CURRENT_TIMESTAMP
  ); 
END
$$ LANGUAGE plpgsql;

-- Triggers

-- Prevent Rating if check_event_complete is false
DROP TRIGGER IF EXISTS CanRate ON Participacao;

CREATE OR REPLACE FUNCTION trigger_canRate() RETURNS TRIGGER AS $$
BEGIN
  IF NOT check_event_complete(New.eventId) THEN
    RAISE EXCEPTION 'Event is not complete yet';
  END IF;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER canRate BEFORE INSERT ON Participacao EXECUTE PROCEDURE trigger_canRate();