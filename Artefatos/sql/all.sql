DROP SCHEMA IF EXISTS final CASCADE;
CREATE SCHEMA final;
SET SCHEMA 'final';

-- CREATE TABLE
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
  username VARCHAR(16) UNIQUE NOT NULL CHECK (username ~* '^[A-Za-z][A-Za-z0-9\.\-_]{2,15}$'),
  password TEXT NOT NULL CHECK (LENGTH(password) >= 8),
  foto TEXT,
  descricao TEXT DEFAULT 'Hello, im a test user for EventBook',
  datacriacao DATE NOT NULL DEFAULT(current_date),
  email VARCHAR(100) UNIQUE NOT NULL CHECK (email ~* '^[^\s@]+@[^\s@]+\.[^\s@.]+$'),
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
  --"data" TIMESTAMP NOT NULL CHECK ("data" >= CURRENT_TIMESTAMP),--
  datacomentario DATE NOT NULL DEFAULT(current_date),
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
  username VARCHAR(16) UNIQUE NOT NULL CHECK (username ~* '^[A-Za-z][A-Za-z0-9\.\-_]{2,15}$'),
  password TEXT NOT NULL CHECK (LENGTH(password) >= 8),
  email VARCHAR(100) UNIQUE NOT NULL CHECK (email ~* '^[^\s@]+@[^\s@]+\.[^\s@.]+$')
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

-- FUNCTIONS
-- Check if event is completed
CREATE FUNCTION check_event_complete(INTEGER) RETURNS BOOLEAN AS $$
BEGIN
  RETURN EXISTS (
    SELECT idEvento, dataInicio, duracao
    FROM Evento
    WHERE
      idEvento = $1 AND
      dataInicio + (duracao * INTERVAL '1 second') >= CURRENT_TIMESTAMP
  );
END;
$$ LANGUAGE plpgsql;

-- Check if user participated in event
CREATE FUNCTION check_participation(INTEGER) RETURNS BOOLEAN AS $$
BEGIN
  RETURN EXISTS (
    SELECT Evento.idEvento, idParticipante
    FROM Evento
    JOIN Participacao ON Evento.idEvento = Participacao.idEvento
    WHERE Participacao.idParticipante = $1
  );
END;
$$ LANGUAGE plpgsql;

-- Check if user can vote in the comment
CREATE FUNCTION check_vote(INTEGER, INTEGER) RETURNS BOOLEAN AS $$
BEGIN
  RETURN EXISTS (
    SELECT IdComentario, IdComentador
    FROM Comentario
    WHERE
      Comentario.IdComentario = $1 AND
      Comentario.IdComentador = $2
  );
END;
$$ LANGUAGE plpgsql;
-- END OF FUNCTIONS

-- TRIGGERS
-- Prevent Rating if user did not participate or the event is not completed
CREATE FUNCTION check_insert_participation() RETURNS TRIGGER AS $$
BEGIN
  IF (New.classificacao != NULL || New.comentario != NULL) THEN
    RAISE EXCEPTION 'Only previously existing participants can vote';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION check_can_rate() RETURNS TRIGGER AS $$
BEGIN
  IF NOT check_participation(New.idParticipante) THEN
      RAISE EXCEPTION 'Current user did not participate in the event';
  END IF;
  IF NOT check_event_complete(New.idEvento) THEN
    RAISE EXCEPTION 'Event is not complete yet';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER can_rate BEFORE INSERT ON Participacao
FOR EACH ROW
EXECUTE PROCEDURE check_insert_participation();

CREATE TRIGGER can_participate BEFORE UPDATE ON Participacao
FOR EACH ROW
EXECUTE PROCEDURE check_can_rate();

-- Prevent Users from following themselves
CREATE FUNCTION trigger_canFollow() RETURNS TRIGGER AS $$
BEGIN
  IF (New.IdSeguidor = New.IdSeguido) THEN
      RAISE EXCEPTION 'Users cannot follow themselves';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanFollow BEFORE INSERT ON Seguidor
FOR EACH ROW
EXECUTE PROCEDURE trigger_canFollow();

-- Prevent Users from voting on their own comments
CREATE FUNCTION trigger_canVote() RETURNS TRIGGER AS $$
BEGIN
  IF check_vote(New.IdComentario, New.IdVotante) THEN
    RAISE EXCEPTION 'Users cannot vote on their own comments';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER CanVote BEFORE INSERT ON ComentarioVoto
FOR EACH ROW
EXECUTE PROCEDURE trigger_canVote();
-- END OF TRIGGERS

-- INSERTS
-- Reset Serial Sequences to 1
ALTER SEQUENCE evento_idevento_seq RESTART WITH 1;
ALTER SEQUENCE utilizador_idutilizador_seq RESTART WITH 1;
ALTER SEQUENCE sondagem_idsondagem_seq RESTART WITH 1;
ALTER SEQUENCE opcao_idopcao_seq RESTART WITH 1;
ALTER SEQUENCE comentario_idcomentario_seq RESTART WITH 1;
ALTER SEQUENCE pais_idpais_seq RESTART WITH 1;
ALTER SEQUENCE administrador_idadministrador_seq RESTART WITH 1;
ALTER SEQUENCE album_idalbum_seq RESTART WITH 1;
ALTER SEQUENCE imagem_idimagem_seq RESTART WITH 1;

-- Tabela "Pais"
INSERT INTO pais (nome) VALUES ('Afghanistan');
INSERT INTO pais (nome) VALUES ('Albania');
INSERT INTO pais (nome) VALUES ('Algeria');
INSERT INTO pais (nome) VALUES ('American Samoa');
INSERT INTO pais (nome) VALUES ('Andorra');
INSERT INTO pais (nome) VALUES ('Angola');
INSERT INTO pais (nome) VALUES ('Anguilla');
INSERT INTO pais (nome) VALUES ('Antarctica');
INSERT INTO pais (nome) VALUES ('Antigua and Barbuda');
INSERT INTO pais (nome) VALUES ('Argentina');
INSERT INTO pais (nome) VALUES ('Armenia');
INSERT INTO pais (nome) VALUES ('Armenia');
INSERT INTO pais (nome) VALUES ('Aruba');
INSERT INTO pais (nome) VALUES ('Australia');
INSERT INTO pais (nome) VALUES ('Austria');
INSERT INTO pais (nome) VALUES ('Azerbaijan');
INSERT INTO pais (nome) VALUES ('Azerbaijan');
INSERT INTO pais (nome) VALUES ('Bahamas');
INSERT INTO pais (nome) VALUES ('Bahrain');
INSERT INTO pais (nome) VALUES ('Bangladesh');
INSERT INTO pais (nome) VALUES ('Barbados');
INSERT INTO pais (nome) VALUES ('Belarus');
INSERT INTO pais (nome) VALUES ('Belgium');
INSERT INTO pais (nome) VALUES ('Belize');
INSERT INTO pais (nome) VALUES ('Benin');
INSERT INTO pais (nome) VALUES ('Bermuda');
INSERT INTO pais (nome) VALUES ('Bhutan');
INSERT INTO pais (nome) VALUES ('Bolivia');
INSERT INTO pais (nome) VALUES ('Bosnia and Herzegovina');
INSERT INTO pais (nome) VALUES ('Botswana');
INSERT INTO pais (nome) VALUES ('Bouvet Island');
INSERT INTO pais (nome) VALUES ('Brazil');
INSERT INTO pais (nome) VALUES ('British Indian Ocean Territory');
INSERT INTO pais (nome) VALUES ('Brunei Darussalam');
INSERT INTO pais (nome) VALUES ('Bulgaria');
INSERT INTO pais (nome) VALUES ('Burkina Faso');
INSERT INTO pais (nome) VALUES ('Burundi');
INSERT INTO pais (nome) VALUES ('Cambodia');
INSERT INTO pais (nome) VALUES ('Cameroon');
INSERT INTO pais (nome) VALUES ('Canada');
INSERT INTO pais (nome) VALUES ('Cape Verde');
INSERT INTO pais (nome) VALUES ('Cayman Islands');
INSERT INTO pais (nome) VALUES ('Central African Republic');
INSERT INTO pais (nome) VALUES ('Chad');
INSERT INTO pais (nome) VALUES ('Chile');
INSERT INTO pais (nome) VALUES ('China');
INSERT INTO pais (nome) VALUES ('Christmas Island');
INSERT INTO pais (nome) VALUES ('Cocos');
INSERT INTO pais (nome) VALUES ('Colombia');
INSERT INTO pais (nome) VALUES ('Comoros');
INSERT INTO pais (nome) VALUES ('Congo');
INSERT INTO pais (nome) VALUES ('Congo, The Democratic Republic of The');
INSERT INTO pais (nome) VALUES ('Cook Islands');
INSERT INTO pais (nome) VALUES ('Costa Rica');
INSERT INTO pais (nome) VALUES ('Cote D''ivoire');
INSERT INTO pais (nome) VALUES ('Croatia');
INSERT INTO pais (nome) VALUES ('Cuba');
INSERT INTO pais (nome) VALUES ('Cyprus');
INSERT INTO pais (nome) VALUES ('Czech Republic');
INSERT INTO pais (nome) VALUES ('Denmark');
INSERT INTO pais (nome) VALUES ('Djibouti');
INSERT INTO pais (nome) VALUES ('Dominica');
INSERT INTO pais (nome) VALUES ('Dominican Republic');
INSERT INTO pais (nome) VALUES ('Easter Island');
INSERT INTO pais (nome) VALUES ('Ecuador');
INSERT INTO pais (nome) VALUES ('Egypt');
INSERT INTO pais (nome) VALUES ('El Salvador');
INSERT INTO pais (nome) VALUES ('Equatorial Guinea');
INSERT INTO pais (nome) VALUES ('Eritrea');
INSERT INTO pais (nome) VALUES ('Estonia');
INSERT INTO pais (nome) VALUES ('Ethiopia');
INSERT INTO pais (nome) VALUES ('Falkland Islands');
INSERT INTO pais (nome) VALUES ('Faroe Islands');
INSERT INTO pais (nome) VALUES ('Fiji');
INSERT INTO pais (nome) VALUES ('Finland');
INSERT INTO pais (nome) VALUES ('France');
INSERT INTO pais (nome) VALUES ('French Guiana');
INSERT INTO pais (nome) VALUES ('French Polynesia');
INSERT INTO pais (nome) VALUES ('French Southern Territories');
INSERT INTO pais (nome) VALUES ('Gabon');
INSERT INTO pais (nome) VALUES ('Gambia');
INSERT INTO pais (nome) VALUES ('Georgia');
INSERT INTO pais (nome) VALUES ('Germany');
INSERT INTO pais (nome) VALUES ('Ghana');
INSERT INTO pais (nome) VALUES ('Gibraltar');
INSERT INTO pais (nome) VALUES ('Greece');
INSERT INTO pais (nome) VALUES ('Greenland');
INSERT INTO pais (nome) VALUES ('Grenada');
INSERT INTO pais (nome) VALUES ('Guadeloupe');
INSERT INTO pais (nome) VALUES ('Guam');
INSERT INTO pais (nome) VALUES ('Guatemala');
INSERT INTO pais (nome) VALUES ('Guinea');
INSERT INTO pais (nome) VALUES ('Guinea-bissau');
INSERT INTO pais (nome) VALUES ('Guyana');
INSERT INTO pais (nome) VALUES ('Haiti');
INSERT INTO pais (nome) VALUES ('Heard Island and Mcdonald Islands');
INSERT INTO pais (nome) VALUES ('Honduras');
INSERT INTO pais (nome) VALUES ('Hong Kong');
INSERT INTO pais (nome) VALUES ('Hungary');
INSERT INTO pais (nome) VALUES ('Iceland');
INSERT INTO pais (nome) VALUES ('India');
INSERT INTO pais (nome) VALUES ('Indonesia');
INSERT INTO pais (nome) VALUES ('Indonesia');
INSERT INTO pais (nome) VALUES ('Iran');
INSERT INTO pais (nome) VALUES ('Iraq');
INSERT INTO pais (nome) VALUES ('Ireland');
INSERT INTO pais (nome) VALUES ('Israel');
INSERT INTO pais (nome) VALUES ('Italy');
INSERT INTO pais (nome) VALUES ('Jamaica');
INSERT INTO pais (nome) VALUES ('Japan');
INSERT INTO pais (nome) VALUES ('Jordan');
INSERT INTO pais (nome) VALUES ('Kazakhstan');
INSERT INTO pais (nome) VALUES ('Kazakhstan');
INSERT INTO pais (nome) VALUES ('Keeling Islands');
INSERT INTO pais (nome) VALUES ('Kenya');
INSERT INTO pais (nome) VALUES ('Kiribati');
INSERT INTO pais (nome) VALUES ('Korea, North');
INSERT INTO pais (nome) VALUES ('Korea, South');
INSERT INTO pais (nome) VALUES ('Kosovo');
INSERT INTO pais (nome) VALUES ('Kuwait');
INSERT INTO pais (nome) VALUES ('Kyrgyzstan');
INSERT INTO pais (nome) VALUES ('Laos');
INSERT INTO pais (nome) VALUES ('Latvia');
INSERT INTO pais (nome) VALUES ('Lebanon');
INSERT INTO pais (nome) VALUES ('Lesotho');
INSERT INTO pais (nome) VALUES ('Liberia');
INSERT INTO pais (nome) VALUES ('Libyan Arab Jamahiriya');
INSERT INTO pais (nome) VALUES ('Liechtenstein');
INSERT INTO pais (nome) VALUES ('Lithuania');
INSERT INTO pais (nome) VALUES ('Luxembourg');
INSERT INTO pais (nome) VALUES ('Macau');
INSERT INTO pais (nome) VALUES ('Macedonia');
INSERT INTO pais (nome) VALUES ('Madagascar');
INSERT INTO pais (nome) VALUES ('Malawi');
INSERT INTO pais (nome) VALUES ('Malaysia');
INSERT INTO pais (nome) VALUES ('Maldives');
INSERT INTO pais (nome) VALUES ('Mali');
INSERT INTO pais (nome) VALUES ('Malta');
INSERT INTO pais (nome) VALUES ('Malvinas');
INSERT INTO pais (nome) VALUES ('Marshall Islands');
INSERT INTO pais (nome) VALUES ('Martinique');
INSERT INTO pais (nome) VALUES ('Mauritania');
INSERT INTO pais (nome) VALUES ('Mauritius');
INSERT INTO pais (nome) VALUES ('Mayotte');
INSERT INTO pais (nome) VALUES ('Mexico');
INSERT INTO pais (nome) VALUES ('Micronesia, Federated States of');
INSERT INTO pais (nome) VALUES ('Moldova, Republic of');
INSERT INTO pais (nome) VALUES ('Monaco');
INSERT INTO pais (nome) VALUES ('Mongolia');
INSERT INTO pais (nome) VALUES ('Montenegro');
INSERT INTO pais (nome) VALUES ('Montserrat');
INSERT INTO pais (nome) VALUES ('Morocco');
INSERT INTO pais (nome) VALUES ('Mozambique');
INSERT INTO pais (nome) VALUES ('Myanmar');
INSERT INTO pais (nome) VALUES ('Namibia');
INSERT INTO pais (nome) VALUES ('Nauru');
INSERT INTO pais (nome) VALUES ('Nepal');
INSERT INTO pais (nome) VALUES ('Netherlands');
INSERT INTO pais (nome) VALUES ('Netherlands Antilles');
INSERT INTO pais (nome) VALUES ('New Caledonia');
INSERT INTO pais (nome) VALUES ('New Zealand');
INSERT INTO pais (nome) VALUES ('Nicaragua');
INSERT INTO pais (nome) VALUES ('Niger');
INSERT INTO pais (nome) VALUES ('Nigeria');
INSERT INTO pais (nome) VALUES ('Niue');
INSERT INTO pais (nome) VALUES ('Norfolk Island');
INSERT INTO pais (nome) VALUES ('Northern Mariana Islands');
INSERT INTO pais (nome) VALUES ('Norway');
INSERT INTO pais (nome) VALUES ('Oman');
INSERT INTO pais (nome) VALUES ('Pakistan');
INSERT INTO pais (nome) VALUES ('Palau');
INSERT INTO pais (nome) VALUES ('Palestinian Territory');
INSERT INTO pais (nome) VALUES ('Panama');
INSERT INTO pais (nome) VALUES ('Papua New Guinea');
INSERT INTO pais (nome) VALUES ('Paraguay');
INSERT INTO pais (nome) VALUES ('Peru');
INSERT INTO pais (nome) VALUES ('Philippines');
INSERT INTO pais (nome) VALUES ('Pitcairn');
INSERT INTO pais (nome) VALUES ('Poland');
INSERT INTO pais (nome) VALUES ('Portugal');
INSERT INTO pais (nome) VALUES ('Puerto Rico');
INSERT INTO pais (nome) VALUES ('Qatar');
INSERT INTO pais (nome) VALUES ('Reunion');
INSERT INTO pais (nome) VALUES ('Romania');
INSERT INTO pais (nome) VALUES ('Russia');
INSERT INTO pais (nome) VALUES ('Russia');
INSERT INTO pais (nome) VALUES ('Rwanda');
INSERT INTO pais (nome) VALUES ('Saint Helena');
INSERT INTO pais (nome) VALUES ('Saint Kitts and Nevis');
INSERT INTO pais (nome) VALUES ('Saint Lucia');
INSERT INTO pais (nome) VALUES ('Saint Pierre and Miquelon');
INSERT INTO pais (nome) VALUES ('Saint Vincent and The Grenadines');
INSERT INTO pais (nome) VALUES ('Samoa');
INSERT INTO pais (nome) VALUES ('San Marino');
INSERT INTO pais (nome) VALUES ('Sao Tome and Principe');
INSERT INTO pais (nome) VALUES ('Saudi Arabia');
INSERT INTO pais (nome) VALUES ('Senegal');
INSERT INTO pais (nome) VALUES ('Serbia and Montenegro');
INSERT INTO pais (nome) VALUES ('Seychelles');
INSERT INTO pais (nome) VALUES ('Sierra Leone');
INSERT INTO pais (nome) VALUES ('Singapore');
INSERT INTO pais (nome) VALUES ('Slovakia');
INSERT INTO pais (nome) VALUES ('Slovenia');
INSERT INTO pais (nome) VALUES ('Solomon Islands');
INSERT INTO pais (nome) VALUES ('Somalia');
INSERT INTO pais (nome) VALUES ('South Africa');
INSERT INTO pais (nome) VALUES ('South Georgia and The South Sandwich Islands');
INSERT INTO pais (nome) VALUES ('Spain');
INSERT INTO pais (nome) VALUES ('Sri Lanka');
INSERT INTO pais (nome) VALUES ('Sudan');
INSERT INTO pais (nome) VALUES ('Suriname');
INSERT INTO pais (nome) VALUES ('Svalbard and Jan Mayen');
INSERT INTO pais (nome) VALUES ('Swaziland');
INSERT INTO pais (nome) VALUES ('Sweden');
INSERT INTO pais (nome) VALUES ('Switzerland');
INSERT INTO pais (nome) VALUES ('Syria');
INSERT INTO pais (nome) VALUES ('Taiwan');
INSERT INTO pais (nome) VALUES ('Tajikistan');
INSERT INTO pais (nome) VALUES ('Tanzania, United Republic of');
INSERT INTO pais (nome) VALUES ('Thailand');
INSERT INTO pais (nome) VALUES ('Timor-leste');
INSERT INTO pais (nome) VALUES ('Togo');
INSERT INTO pais (nome) VALUES ('Tokelau');
INSERT INTO pais (nome) VALUES ('Tonga');
INSERT INTO pais (nome) VALUES ('Trinidad and Tobago');
INSERT INTO pais (nome) VALUES ('Tunisia');
INSERT INTO pais (nome) VALUES ('Turkey');
INSERT INTO pais (nome) VALUES ('Turkey');
INSERT INTO pais (nome) VALUES ('Turkmenistan');
INSERT INTO pais (nome) VALUES ('Turks and Caicos Islands');
INSERT INTO pais (nome) VALUES ('Tuvalu');
INSERT INTO pais (nome) VALUES ('Uganda');
INSERT INTO pais (nome) VALUES ('Ukraine');
INSERT INTO pais (nome) VALUES ('United Arab Emirates');
INSERT INTO pais (nome) VALUES ('United Kingdom');
INSERT INTO pais (nome) VALUES ('United States');
INSERT INTO pais (nome) VALUES ('United States Minor Outlying Islands');
INSERT INTO pais (nome) VALUES ('Uruguay');
INSERT INTO pais (nome) VALUES ('Uzbekistan');
INSERT INTO pais (nome) VALUES ('Vanuatu');
INSERT INTO pais (nome) VALUES ('Vatican City');
INSERT INTO pais (nome) VALUES ('Venezuela');
INSERT INTO pais (nome) VALUES ('Vietnam');
INSERT INTO pais (nome) VALUES ('Virgin Islands, British');
INSERT INTO pais (nome) VALUES ('Virgin Islands, U.S.');
INSERT INTO pais (nome) VALUES ('Wallis and Futuna');
INSERT INTO pais (nome) VALUES ('Western Sahara');
INSERT INTO pais (nome) VALUES ('Yemen');
INSERT INTO pais (nome) VALUES ('Yemen');
INSERT INTO pais (nome) VALUES ('Zambia');
INSERT INTO pais (nome) VALUES ('Zimbabwe');

-- Tabela "Evento"

INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('Be better, be', 'data/default/capa.jpg', 'water my friend Bruce Lee', 'FEUP', '2016-12-29', 60, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('The Great Artists', 'data/default/capa.jpg', 'The best Prog Rock and Epic Music', 'Viana', '2016-07-05', 180, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('CARL COX', 'data/default/capa.jpg', 'Bailarico', 'Viana', '2016-11-19', 150, FALSE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('champions league', 'data/default/capa.jpg', 'futebol', 'Lisboa', '2016-10-29', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('alfandega', 'data/default/capa.jpg', 'Dançar', 'Caminha', '2016-08-22', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('matador', 'data/default/capa.jpg', 'da-lhe bues', 'Pacha', '2016-08-23', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('neopop', 'data/default/capa.jpg', 'melhor festival', 'Viana', '2016-08-07', 150, FALSE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('LBAW', 'data/default/capa.jpg', 'A10', 'Porto', '2016-10-14', 150, FALSE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('IART', 'data/default/capa.jpg', 'Biometria', 'Biblioteca FMUP', '2016-12-10', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('COMP', 'data/default/capa.jpg', 'MATDSL', 'Biblioteca FEUP', '2016-11-12', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('PPIN', 'data/default/capa.jpg', 'Comunicacao', 'Queijos', '2016-06-21', 150, TRUE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('SDIS', 'data/default/capa.jpg', 'multicast', 'FEUP', '2016-07-11', 150, FALSE);
INSERT INTO evento (titulo, capa, descricao, localizacao, dataInicio, duracao, publico) VALUES ('euro league', 'data/default/capa.jpg', 'futebol', 'Porto', '2016-07-05', 150, TRUE);

-- Tabela "Utilizador"
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Xavi', 'diogodometro', 'whirlpool:1000:MH5KiDFBw3T2NY0ZFULwwG09ZWwwdSyN:v1OCQAtowGxwpucDp2r9deUjQ617t6Fzq+DSWH5yaOhPAgjDxlm9lySoO9YKjcKxdBR0g0HuiiZ1Ro0QRqDHew==', 'data/default/foto.png', 'diogoxavier95@hotmail.com', 177);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Diogo', 'avc', 'whirlpool:1000:IxTEkfT0DwQkBbcdat2Y2a+DZFHbLaDn:0Z/Y7+Z3UD8grt4S3n1XiiFYBr5w/srcaEjAAO2/3TBxF7XhYkE2DuRvnSvCax7tz0dSLmVeqA+kG59SR5Qu9g==', 'data/default/foto.png', 'avc@hotmail.com', 1);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Hugo', 'drumond', 'whirlpool:1000:ijlhOZCh7o6X9ySJ7pHqNeR4u8uvTnIO:fc/BYd3NXyN07f32u4sQO/tybbmORT8rZj/FhW2f2Bp0pDx3w4AaHBl0fkSf0tzNcIy2EotssIsw0NXw1U0MIA==', 'data/default/foto.png', 'drumond@hotmail.com', 6);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Joao Fernandes', 'faker', 'whirlpool:1000:N6qDDFLOA2HO346NLaaPduYSZskytTGG:i1VFYerbAPmsmGxOweFCV9bpIpwdSSeSnHhGgsh37SV0bqe5YGptyWI8pWey9J4tLqnzH+Mr0RCBjekmJatAKA==', 'data/default/foto.png', 'joao_zinho@hotmail.com', 150);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Antonio Pina', 'pinnoy', 'whirlpool:1000:GlmCvmrkFGUacElyi1K7BJcIxBIVEBDz:w4o591MDJfj6X/z9cfFfj+9XE1IkkInTCP/Qcd2HGp33oZWumPtTqCOIc/K6pbQ+FGOJ3OLVlKv1uYGOk+eH1A==', 'data/default/foto.png', 'p1n4@hotmail.com', 12);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Carina', 'carinafonteseca', 'whirlpool:1000:TrS6B0ylD8LtV2UakbTCBubbgmZHSsoM:gS7lol/9mHrK3EjF6jB2mCm5EjG8t7ZnRRXCVQFA815IV6ed4zsowKlty4+174Nwz5Npf1dWDtiEJRqVgxE9YQ==', 'data/default/foto.png', 'cursos@fe.up.pt', 21);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Barack Obama', 'blackhouse', 'whirlpool:1000:Kg/b79D44EJuOY+dCglMX2vfpnVTp0x0:n7XX03Tbf8xPkopP0RPrXhGw4J7DAt0t0urlHo2QpVEwVmOayyM4iRdZQ+SrSS6Y6F+W43/CKn6rtPZ9tnQLKA==', 'data/default/foto.png', 'BarackObama@whitehouse.us', 74);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Jose Mourinho', 'zemoura', 'whirlpool:1000:d0Jd0rQoB7n3y8f3t+IzveDrbUyhHuLx:etpCkKxNzrWaUyIal+DPBPl4wA7V3Ad/Jk0K9hvzdJp6kM9OzxhAMZRpX3AneR8HD1LvybuKMN98e+FoFcW6Cw==', 'data/default/foto.png', 'mou@mou.pt', 1);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Cristiano Ronaldo', 'cris85', 'whirlpool:1000:ucvPMf5Qklg9wx99itefPuSG70+E34PT:WXqSYxcP6tuU8nSpeQtI8ulJafIEBxbcD/0UUZpqVmifqkbjS99JzZY7gBoUYUX7LaylP2uim69vIF7JqrVYgA==', 'data/default/foto.png', 'ganhomaisquetu@ganhobues.com', 142);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('José Sócrates', 'josesocas', 'whirlpool:1000:G5m4Db/9mTf2ecDqJ+JFMft+eAqFo4+y:EyPkUdwzjIvmj0nM+LS8kX5823hsZ9SqjgP2CUTP/Lfl1uTZgspsWWS15prpWYa7eGOTj24r53lhRJ563Lt7cQ==', 'data/default/foto.png', 'maconaria_22@macons.pt', 170);
INSERT INTO utilizador (nome, username, password, foto, email, IdPais) VALUES ('Cavaco Silva', 'cavaquinho', 'whirlpool:1000:Ru0cV0q9k+YmyLRMz8rhhE1RotI+WC/k:/Nnj7TVyOtVRGXT064fBpIjFq185oOGxMYORr/IGkT9Ja3FGAPo/vUS0xlsRuw6VQ5yQgpnqvzam/Ah0NQENoA==', 'data/default/foto.png', 'maconaria_33@macons.pt', 170);

-- Tabela "Sondagem"
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Onde realizar?', '2016-12-30', TRUE, 1);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Prog Rock or Epic?', '2016-07-06', FALSE, 2);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Musica preferida?', '2016-11-21', FALSE, 3);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Vencedor?', '2017-10-31', FALSE, 4);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Interessado em que festa?', '2016-08-25', TRUE, 5);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Musica preferida de Matador?', '2016-07-25', FALSE, 6);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Artista que mais gostaste de ver ver no neostage?', '2016-08-10', FALSE, 7);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Qual é a tua linguagem de programaçao web preferida?', '2016-10-19', FALSE, 8);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Qual é o teu algoritmo best-first preferido?', '2016-12-01', FALSE, 9);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Qual é a fase do compilador preferida?', '2016-11-17', FALSE, 10);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Enuncia os signos de linguagem nao verbal que mais gostas', '2016-07-23', TRUE, 11);
INSERT INTO sondagem (descricao, data, escolhaMultipla, IdEvento) VALUES ('Qual é o teu protocolo de transferencia de dados preferido?', '2016-07-17', FALSE, 12);

-- Tabela "Opcao"
INSERT INTO opcao (descricao, IdSondagem) VALUES ('Queijos', 1);
INSERT INTO opcao (descricao, IdSondagem) VALUES ('B003', 1);
INSERT INTO opcao (descricao, IdSondagem) VALUES ('B330', 1);
INSERT INTO opcao (descricao, IdSondagem) VALUES ('Biblioteca', 1);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Prog Rock', 2);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Epic', 2);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Jaguar', 3);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Fantasee', 3);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Bayern', 4);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Real Madrid', 4);
INSERT INTO opcao (descricao, idSondagem) VALUES ('ISEP finos a 20 centimos', 5);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Fim de ano', 5);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Da hustle', 6);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Klay', 6);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Maya Jane Coles', 7);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Richie Hawtin', 7);
INSERT INTO opcao (descricao, idSondagem) VALUES ('PHP', 8);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Javascript', 8);
INSERT INTO opcao (descricao, idSondagem) VALUES ('A*', 9);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Uniform Cost - Branch & Bound', 9);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Analise semantica', 10);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Analise lexical', 10);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Sorriso', 11);
INSERT INTO opcao (descricao, idSondagem) VALUES ('Gestos', 11);
INSERT INTO opcao (descricao, idSondagem) VALUES ('TCP', 12);
INSERT INTO opcao (descricao, idSondagem) VALUES ('UDP', 12);

-- Tabela "Comentario"
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('LoLOlOLolOloLol', '2016-07-01', 3, 1, NULL);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('bruce fucking lee', '2016-08-01', 3, 1, 1);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('jackie jan > bruce lee', '2016-06-24', 2, 1, 1);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('ahahahah que riso', '2016-08-11', 2, 1, 1);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('eargasm', '2016-07-12', 1, 2, NULL);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('boa cena', '2016-07-30', 2, 2, 5);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('adoro adoro adoro', '2016-08-19', 2, 2, 5);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('que grandes sons!!', '2016-07-20', 1, 2, 5);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('quatrocentos e vinte', '2016-08-12', 1, 3, NULL);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('oh yes oh yes', '2016-09-29', 2, 3, 9);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('fantastic fantastic', '2016-08-30', 2, 3, 9);
INSERT INTO comentario (texto, datacomentario, IdComentador, IdEvento, IdComentarioPai) VALUES ('hahahhaah i love you', '2016-09-19', 1, 3, 9);

-- Tabela "Seguidor"
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (2, 1, '2016-06-23');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (3, 1, '2016-06-18');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (2, 3, '2016-06-20');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (2, 7, '2016-06-30');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (3, 2, '2016-07-18');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (4, 5, '2016-07-26');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (5, 4, '2016-07-02');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (9, 10, '2016-08-01');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (10, 9, '2016-08-29');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (7, 8, '2016-08-06');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (8, 7, '2016-10-10');
INSERT INTO seguidor (IdSeguidor, IdSeguido, data) VALUES (6, 5, '2016-10-24');

-- Tabela "Participacao"
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (2, 2, 5, 'Adorei!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (1, 3, 3, 'Nem me aqueceu nem arrefeceu!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (3, 1, 5, 'RECOMENDO VIVAMENTE!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (3, 2, 5, 'Adorei!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (3, 7, 5, 'Pro ano estou ca!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (2, 8, 3, 'Pouca variedade..');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (2, 10, 4, 'Bem fixe!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (3, 9, 5, 'Nota maxima!!!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (2, 9, 2, 'Jamais voltarei a pôr os pés num evento deste género!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (8, 5, 4, 'Continuem com a boa organização da cadeira!');
INSERT INTO participacao (IdEvento, IdParticipante, classificacao, comentario) VALUES (10, 4, 4, 'Uma das cadeiras mais interessantes do MIEIC!');

-- Tabela "Convite"
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (1, 2, '2017-01-10', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (1, 3, '2017-01-11', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (2, 1, '2016-07-08', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (2, 2, '2016-07-20', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (3, 2, '2016-11-24', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (3, 1, '2016-11-30', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (4, 4, '2016-12-20', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (4, 5, '2016-12-27', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (5, 5, '2016-09-01', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (5, 6, '2016-09-02', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (6, 6, '2016-07-24', TRUE);
INSERT INTO convite (IdEvento, IdConvidado, data, resposta) VALUES (6, 8, '2016-07-31', TRUE);

-- Tabela "Anfitriao"
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (1, 7);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (2, 5);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (3, 1);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (4, 2);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (5, 9);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (6, 8);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (7, 5);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (8, 6);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (9, 4);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (10, 2);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (10, 1);
INSERT INTO Anfitriao (IdEvento, IdAnfitriao) VALUES (10, 3);

-- Tabela "UtilizadorOpcao"
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (1, 5);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (1, 7);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (2, 2);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (2, 3);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (3, 2);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (3, 3);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (4, 10);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (5, 11);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (5, 12);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (6, 11);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (6, 12);
INSERT INTO utilizadoropcao (IdUtilizador, IdOpcao) VALUES (8, 8);

-- Tabela "ComentarioVoto"
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (2, 2, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (3, 3, FALSE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (3, 1, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (4, 3, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (5, 2, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (6, 1, FALSE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (6, 3, FALSE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (7, 1, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (8, 2, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (9, 2, FALSE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (10, 1, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (11, 1, TRUE);
INSERT INTO comentariovoto (IdComentario, IdVotante, positivo) VALUES (12, 2, TRUE);

-- Tabela "Album"
INSERT INTO album (nome, descricao, idEvento) VALUES('First Session', 'Brainstorming and Meetup', 1);
INSERT INTO album (nome, descricao, idEvento) VALUES('Second Session', 'Games', 1);
INSERT INTO album (nome, descricao, idEvento) VALUES('Third Session', 'Bye bye' , 1);
INSERT INTO album (nome, descricao, idEvento) VALUES('Progressive Rock', 'Pink Floyd, Led Zeppelin, King Crimson, Camel', 2);
INSERT INTO album (nome, descricao, idEvento) VALUES('Epic Music',  'Hans Zimmer, Thomas Bergersen, Audiomachine, Two Steps From Hell', 2);
INSERT INTO album (nome, descricao, idEvento) VALUES('Malucos', 'Os loucos da festa', 3);
INSERT INTO album (nome, descricao, idEvento) VALUES('Final Milao', 'Highlights', 4);
INSERT INTO album (nome, descricao, idEvento) VALUES('Segundo dia neopop', 'Matador, pan pot, etc..', 6);
INSERT INTO album (nome, descricao, idEvento) VALUES('Artefactos', 'Exemplos dos diversos artefatos' , 8);
INSERT INTO album (nome, descricao, idEvento) VALUES('ISEP - finos a 20 centimos', 'Melhores momentos deste festão', 5);
INSERT INTO album (nome, descricao, idEvento) VALUES('Material apoio',  'Screenshots para resolver bug do bootstrapping duma network', 12);
INSERT INTO album (nome, descricao, idEvento) VALUES('Neopop all days', 'As melhores fotos desta ediçao', 7);

-- Tabela "Imagem"
INSERT INTO imagem(caminho, data, idAlbum) VALUES('session1.jpg', '2016-12-30', 1);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('session2.jpg', '2017-01-14', 2);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('session3.jpg', '2017-01-15', 3);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('pinkfloyd-rocking.jpg', '2016-08-12', 4);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('ledzeppelin-rocking.jpg', '2016-08-13', 4);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('hans-zimmer.jpg', '2016-07-10', 5);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('audiomachine.jpg', '2016-07-13', 5);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('audiomachine2.jpg', '2016-07-14', 5);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('joaquim.jpg', '2016-11-22', 6);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('alberto.jpg', '2016-11-29', 6);
INSERT INTO imagem(caminho, data, idAlbum) VALUES('gertrudes.jpg', '2016-11-24', 6);

-- Tabela "Administrador
INSERT INTO Administrador(username, email, password) VALUES('theboss', 'theboss@gg.bb', 'queriasbatatasfrias');
INSERT INTO Administrador(username, email, password) VALUES('magical', 'unicorn@blue.sky', '12314nanananana');
INSERT INTO Administrador(username, email, password) VALUES('major', 'majorbeast@gmail.com', 'majorcontrollingthezone');
-- END OF INSERTS

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

-- FULL TEXT SEARCH
-- Find website events, FULL TEXT SEARCH
CREATE VIEW Event_view_fts AS
SELECT  Evento.idEvento as idEvento,
        Evento.titulo as titulo,
        Evento.descricao as descricao,
        Evento.capa as capa,
        Evento.localizacao as localizacao,
        Evento.dataInicio as dataInicio,
        Evento.publico as publico,
        setweight(to_tsvector('english', Evento.titulo), 'A') ||
        setweight(to_tsvector('english', COALESCE(Evento.descricao, '')), 'B') ||
        setweight(to_tsvector('simple', Evento.localizacao), 'C') ||
        setweight(to_tsvector('simple', string_agg(Utilizador.username, ' ')), 'B') AS document
FROM Evento
JOIN Anfitriao ON Anfitriao.idEvento = Evento.idEvento
JOIN Utilizador ON Utilizador.idUtilizador = Anfitriao.IdAnfitriao
GROUP BY Evento.idEvento;

CREATE FUNCTION get_events_public(texto character varying) RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
  SELECT json_agg(row_to_json(search)) INTO result
  FROM
  (SELECT idEvento, titulo, descricao, capa, localizacao, dataInicio
    FROM Event_view_fts
    WHERE
      publico = true AND
      Event_view_fts.document @@ plainto_tsquery(texto)
    ORDER BY ts_rank(Event_view_fts.document, plainto_tsquery(texto)) DESC) AS search;
    return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION get_events_authenticated(idutilizadorrr integer, texto character varying) RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
  SELECT json_agg(row_to_json(search)) INTO result
  FROM
  (SELECT idEvento, titulo, descricao, capa, localizacao, dataInicio
    FROM Event_view_fts
    WHERE
idEvento in (
  select participacao.idevento from participacao where participacao.idparticipante = idutilizadorrr union select convite.idevento from convite where convite.idconvidado = idutilizadorrr union select anfitriao.idevento from anfitriao where anfitriao.idanfitriao = idutilizadorrr) and
      Event_view_fts.document @@ plainto_tsquery(texto)
    ORDER BY ts_rank(Event_view_fts.document, plainto_tsquery(texto)) DESC) AS search;
    return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION get_events_simple_authenticated(idutilizadorrr integer) RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
SELECT json_agg(row_to_json(search)) INTO result
FROM (
  SELECT idEvento, titulo, descricao, capa, localizacao, dataInicio FROM Evento
WHERE idEvento in (
  select participacao.idevento from participacao where participacao.idparticipante = idutilizadorrr union select convite.idevento from convite where convite.idconvidado = idutilizadorrr union select anfitriao.idevento from anfitriao where anfitriao.idanfitriao = idutilizadorrr)
) AS search;
return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION get_events_public_simple() RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
SELECT json_agg(row_to_json(search)) INTO result
FROM (
  SELECT idEvento, titulo, descricao, capa, localizacao, dataInicio FROM Evento
  WHERE publico = true
) AS search;
return result;
END;
$$ LANGUAGE plpgsql;

CREATE VIEW Event_view_user_fts AS
SELECT  Evento.idEvento as idEvento,
        Evento.titulo as titulo,
        Evento.descricao as descricao,
        Evento.capa as capa,
        Evento.localizacao as localizacao,
        Evento.dataInicio as dataInicio,
        Anfitriao.idAnfitriao as idAnfitriao,
        setweight(to_tsvector('english', Evento.titulo), 'A') ||
        setweight(to_tsvector('english', COALESCE(Evento.descricao, '')), 'B') ||
        setweight(to_tsvector('simple', Evento.localizacao), 'C') AS Document
FROM Evento
JOIN Anfitriao ON Anfitriao.idEvento = Evento.idEvento
GROUP BY Evento.idEvento, Anfitriao.idAnfitriao;

CREATE FUNCTION get_my_events(idUtilizadorrr integer, texto character varying) RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
  SELECT json_agg(row_to_json(search)) INTO result
  FROM
  (SELECT idEvento, titulo, descricao, capa, localizacao, dataInicio
FROM Event_view_user_fts
WHERE
  document @@ plainto_tsquery(texto) AND
  idAnfitriao = idUtilizadorrr
ORDER BY ts_rank(Event_view_user_fts.document, plainto_tsquery(texto)) DESC) as search;
return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION get_my_events_simple(idUtilizadorrr integer) RETURNS json AS $$
DECLARE
  result character varying;
BEGIN
SELECT json_agg(row_to_json(search)) INTO result
FROM (
  SELECT Evento.idEvento, titulo, descricao, capa, localizacao, dataInicio
  FROM Evento
  JOIN Anfitriao ON Anfitriao.idEvento = Evento.idEvento
  WHERE Anfitriao.idAnfitriao = idUtilizadorrr) AS search;
return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION get_top_events_public() returns json AS $$
DECLARE
  result character varying;
BEGIN
SELECT json_agg(row_to_json(search)) INTO result
FROM
(SELECT E.idEvento, E.titulo, E.capa, E.descricao, E.localizacao, E.dataInicio, E.duracao, E.publico, P.Numero_de_Participantes
FROM Evento E
INNER JOIN
(
  SELECT idEvento, count(idEvento) AS Numero_de_Participantes
  FROM Participacao
  GROUP BY idEvento
) P ON E.idEvento = P.idEvento
where e.publico = true
ORDER BY Numero_de_Participantes DESC) AS search;
return result;
END;
$$ LANGUAGE plpgsql;
-- END OF FULL TEXT SEARCH

CREATE FUNCTION get_top_events_authenticated(idUtilizadorrr integer) returns json AS $$
DECLARE
  result character varying;
BEGIN
SELECT json_agg(row_to_json(search)) INTO result
FROM
(SELECT E.idEvento, E.titulo, E.capa, E.descricao, E.localizacao, E.dataInicio, E.duracao, E.publico, P.Numero_de_Participantes
FROM Evento E
INNER JOIN
(
  SELECT idEvento, count(idEvento) AS Numero_de_Participantes
  FROM Participacao
  GROUP BY idEvento
) P ON E.idEvento = P.idEvento
WHERE E.idEvento in (
  select participacao.idevento from participacao where participacao.idparticipante = idutilizadorrr union select convite.idevento from convite where convite.idconvidado = idutilizadorrr union select anfitriao.idevento from anfitriao where anfitriao.idanfitriao = idutilizadorrr)
ORDER BY Numero_de_Participantes DESC) AS search;
return result;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION can_view_event(idUtilizadorrr integer, idEventooo integer) returns boolean AS $$
BEGIN
  return exists (
    SELECT * FROM
    (select participacao.idevento from participacao where participacao.idparticipante = idutilizadorrr union select convite.idevento from convite where convite.idconvidado = idutilizadorrr union select anfitriao.idevento from anfitriao where anfitriao.idanfitriao = idutilizadorrr) AS search
    WHERE idevento = idEventooo
  );
END;
$$ LANGUAGE plpgsql;
