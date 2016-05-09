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
