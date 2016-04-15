-- Get login Information of an "Utilizador" by email
SELECT *
FROM Utilizador
WHERE email ILIKE '%mon%';

-- Get login Information of an "Utilizador" by username
SELECT *
FROM Utilizador
WHERE username ILIKE '%drum%';

-- Find the "Eventos" of a given "Utilizador"
SELECT Evento.*
FROM Utilizador, Anfitriao, Evento
WHERE
    (Utilizador.username ILIKE '%mon%' OR Utilizador.email ILIKE '%mon%') AND
  Utilizador.idUtilizador = Anfitriao.idAnfitriao AND
  Anfitriao.idEvento = Evento.idEvento;

-- Find the comments concerning an "Evento"
SELECT
Comentario.idComentario, Comentario.texto, Comentario.data, Utilizador.idUtilizador, Utilizador.username
FROM Comentario, Utilizador
WHERE
  Comentario.idEvento = 1 AND
  Comentario.idComentario = Utilizador.idUtilizador;

-- Get the users that voted on a "Comentario"
SELECT ComentarioVoto.idComentario, Utilizador.idUtilizador, Utilizador.username
FROM ComentarioVoto, Utilizador
WHERE
  ComentarioVoto.idVotante = Utilizador.idUtilizador;

-- Get the number of "Comentario" upvotes
SELECT ComentarioVoto.idComentario, COUNT(positivo)
FROM ComentarioVoto
WHERE positivo is TRUE
GROUP BY ComentarioVoto.idComentario;

-- Get the number of "Comentario" downvotes
SELECT ComentarioVoto.idComentario, COUNT(positivo)
FROM ComentarioVoto
WHERE positivo is FALSE
GROUP BY ComentarioVoto.idComentario;

-- Find the albums and images of an "Evento"
SELECT Album.*, Imagem.*
FROM Album, Imagem
WHERE
  Album.idEvento = 2 AND
  Imagem.idAlbum = Album.idAlbum;

-- Get the "Sondagem" and its "opcao"
SELECT Sondagem.IdSondagem, Sondagem.descricao, Sondagem.data, Sondagem.escolhaMultipla, Opcao.idOpcao, Opcao.descricao
FROM Sondagem, Opcao
WHERE
  Sondagem.idEvento = 1 AND
  Opcao.idSondagem = Sondagem.idSondagem;

-- Get the current results of a "Sondagem"
SELECT Opcao.idOpcao, COUNT(Opcao.idOpcao)
FROM Opcao, UtilizadorOpcao
WHERE
  Opcao.idSondagem = 1 AND
  Opcao.idOpcao = UtilizadorOpcao
GROUP BY Opcao.idOpcao;

-- Get the Participants of an "Evento"
SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
FROM Participacao, Utilizador
WHERE
  Participacao.idEvento = 1 AND
  Participacao.IdParticipante = Utilizador.idUtilizador;

-- Get the people that are Participants of an "Evento" that I follow
SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
FROM Participacao, Seguidor, Utilizador
WHERE
  Participacao.idEvento = 2 AND
  Participacao.IdParticipante IN (SELECT Seguidor.idSeguido FROM Seguidor WHERE Seguidor.idSeguidor = 1) AND
  Utilizador.IdUtilizador = Participacao.IdParticipante;

-- Get the top 10 (if exists at least 10) upcoming public Events

SELECT * FROM Evento
WHERE publico = true
ORDER BY dataInicio ASC
LIMIT 10;
