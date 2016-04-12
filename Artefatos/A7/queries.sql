-- Find "eventos" with a certain "titulo"
SELECT *
FROM Evento
WHERE titulo ILIKE '%be%';

-- Find "utilizadores" with a certain username or email
SELECT *
FROM Utilizador
WHERE
  email ILIKE '%mon%' OR
  username ILIKE '%drum%';

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
  Comentario.idEvento = 1;
  Comentario.idComentario = Utilizador.idUtilizador;

-- Get the users that voted on a "Comentario"
SELECT Utilizador.idUtilizador, Utilizador.username
FROM ComentarioVoto, Utilizador
WHERE
  ComentarioVoto.idVotante = Utilizador.idUtilizador;

-- Get the number of "Comentario" upvotes and downvotes
SELECT COUNT(positivo)
FROM ComentarioVoto
GROUP BY positivo;

-- Find the albums and images of an "Evento"
SELECT Album.*, Imagem.*
FROM Album, Imagem
WHERE
  Album.idEvento = 2 AND
  Image.idAlbum = Album.idAlbum;

-- Get the "Sondagem" and its "opcao"
SELECT Sondagem.IdSondagem, Sondagem.descricao, Sondagem.data, Sondagem.escolhaMultipla, Opcao.idOpcao, Opcao.descricao
FROM Sondagem, Opcao
WHERE
  Sondagem.idEvento = 1 AND
  Opcao.idSondagem = Sondagem.idSondagem;

-- Get the Participants of an "Evento"
SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
FROM Participacao, Utilizador
WHERE
  Participacao.idEvento = 1 AND
  Participacao.idUtilizador = Utilizador.idUtilizador;

-- Get the people that are Participants of an "Evento" that I follow
SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
FROM Participacao, Seguidor, Utilizador
WHERE
  Participacao.idEvento = 2 AND
  Participacao.idUtilizador IN (SELECT Seguidor.idSeguido FROM Seguidor WHERE Seguidor.idSeguidor = 1) AND
  Utilizador.IdUtilizador = Participacao.IdUtilizador;
