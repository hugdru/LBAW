-- Get login Information of an "Utilizador" by email
SELECT *
FROM Utilizador
WHERE email = 'drumond@gmail.com';

-- Get login Information of an "Utilizador" by username
SELECT *
FROM Utilizador
WHERE username = 'blackhouse';

-- Find website events, FULL TEXT SEARCH
SELECT id, title, descricao
FROM (
  SELECT Evento.idEvento as id,
         Evento.titulo as title,
         Evento.descricao as descricao,
         to_tsvector(Evento.titulo) ||
         to_tsvector(COALESCE(Evento.descricao, '')) ||
         to_tsvector(Evento.localizacao) ||
         to_tsvector(string_agg(Utilizador.username, ' ')) AS document
  FROM Evento
  JOIN Anfitriao ON Anfitriao.idEvento = Evento.idEvento
  JOIN Utilizador ON Utilizador.idUtilizador = Anfitriao.IdAnfitriao
  GROUP BY Evento.idEvento) evento_search
WHERE evento_search.document @@ to_tsquery('water');

-- Find website events of a given "Utilizador", FULL TEXT SEARCH
SELECT id, title, descricao
FROM (
  SELECT Evento.idEvento as id,
         Evento.titulo as title,
         Evento.descricao as descricao,
         to_tsvector(Evento.titulo) ||
         to_tsvector(COALESCE(Evento.descricao, '')) ||
         to_tsvector(Evento.localizacao) AS document
  FROM Evento
  JOIN Anfitriao ON Anfitriao.idEvento = Evento.idEvento
  WHERE
    Anfitriao.idAnfitriao = (SELECT Utilizador.idUtilizador FROM Utilizador WHERE Utilizador.username = 'avc')
  GROUP BY Evento.idEvento) evento_search
WHERE evento_search.document @@ to_tsquery('MATDSL');

-- Find the comments concerning an "Evento" and the votes
SELECT Results.idComentario, Results.idComentador, Results.username, Results.texto, Results.data, Results.idComentarioPai, json_object_agg(Results.positivo, Results.voters) AS votes
FROM (
  SELECT Comentario.idComentario, Comentario.idComentador, (SELECT Utilizador.username FROM Utilizador WHERE Utilizador.idUtilizador = Comentario.idComentario) AS username, Comentario.texto, Comentario.data, json_object_agg(Utilizador.idUtilizador, Utilizador.username) AS voters, Comentario.idComentarioPai, positivo, COUNT(positivo) AS count
  FROM Comentario
  JOIN ComentarioVoto ON ComentarioVoto.idComentario = Comentario.idComentario
  JOIN Utilizador ON Utilizador.idUtilizador = ComentarioVoto.idVotante
  WHERE Comentario.idEvento = 1
  GROUP BY Comentario.idComentario, ComentarioVoto.Positivo
) AS Results
GROUP BY Results.idComentario, Results.idComentador, Results.username, Results.texto, Results.data, Results.idComentarioPai;

-- Get only the number of "Comentario" votes and who voted upvoted or downvoted
SELECT ComentarioVoto.idComentario, json_object_agg(Utilizador.idUtilizador, Utilizador.username) AS Votes, positivo, COUNT(positivo)
FROM ComentarioVoto
JOIN Utilizador ON ComentarioVoto.idVotante = Utilizador.idUtilizador
WHERE ComentarioVoto.idComentario = 3
GROUP BY ComentarioVoto.idComentario, ComentarioVoto.Positivo;

-- Get the albums and images of an "Evento"
SELECT Album.idAlbum, Album.nome, Album.descricao, json_agg(json_build_object('id', Imagem.IdImagem, 'caminho', Imagem.caminho, 'data', Imagem.data))
FROM Album
JOIN Imagem ON Imagem.idAlbum = Album.idAlbum
WHERE Album.idEvento = 2
GROUP BY Album.idAlbum;

-- Get the "Sondagem" and its "opcao"
SELECT Sondagem.IdSondagem, Sondagem.descricao, Sondagem.data, Sondagem.escolhaMultipla, json_agg(json_build_object('id', Opcao.idOpcao, 'descricao', Opcao.descricao))
FROM Sondagem
JOIN Opcao ON Opcao.idSondagem = Sondagem.idSondagem
WHERE Sondagem.idEvento = 1
GROUP BY Sondagem.idSondagem;

-- Get the current results of a "Sondagem"
SELECT json_agg(ResultsById.ResultsById) AS SondagemResults
FROM (
  SELECT json_build_object('id', Opcao.idOpcao, 'votes', COUNT(Opcao.idOpcao)) AS ResultsById
  FROM Opcao
  JOIN UtilizadorOpcao ON Opcao.idOpcao = UtilizadorOpcao.idOpcao
  WHERE Opcao.idSondagem = 1
  GROUP BY Opcao.idOpcao
) AS ResultsById;

-- Get the Participants of an "Evento"
SELECT Utilizador.idUtilizador, Utilizador.username, Participacao.classificacao, Participacao.comentario
FROM Participacao
JOIN Utilizador ON Participacao.IdParticipante = Utilizador.idUtilizador
WHERE Participacao.idEvento = 1;

-- Get the people that are Participants of an "Evento" that I follow
SELECT Utilizador.idUtilizador, Utilizador.username
FROM Participacao
JOIN Seguidor ON Participacao.IdParticipante IN (SELECT Seguidor.idSeguido FROM Seguidor WHERE Seguidor.idSeguidor = 7)
JOIN Utilizador ON Utilizador.IdUtilizador = Participacao.IdParticipante AND Seguidor.idSeguido = Participacao.IdParticipante
WHERE Participacao.idEvento = 2;

-- Get the top 10 (if exists at least 10) upcoming public Events
SELECT * FROM Evento
WHERE publico = true
ORDER BY dataInicio ASC
LIMIT 10;
