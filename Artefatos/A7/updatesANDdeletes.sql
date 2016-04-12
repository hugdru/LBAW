-- UPDATES

UPDATE Evento
SET descricao = 'A festa e fixe'
WHERE idEvento = 1;

UPDATE Evento
SET dataInicio = '2016-08-04'
WHERE idEvento = 3;

UPDATE Utilizador
SET password = 'arrozdechocolate'
WHERE idUtilizador =  1;

UPDATE Utilizador
SET foto = 'top.png'
WHERE idUtilizador = 2;

UPDATE Sondagem
SET escolhaMultipla = 'TRUE'
WHERE idSondagem = 1;

UPDATE UtilizadorOpcao
SET IdOpcao = 5,
WHERE IdUtilizador = 1 AND IdOpcao = 3;

UPDATE Convite
SET resposta = 'TRUE'
WHERE idEvento = 1 AND idConvidado = 2;


-- DELETES

DELETE FROM Comentario
WHERE IdComentario = 1;

DELETE FROM Opcao
WHERE idOpcao = 10;

DELETE FROM Seguidor
WHERE IdSeguidor = 2 AND IdSeguido = 1;

DELETE FROM UtilizadorOpcao
WHERE IdUtilizador = 2 AND IdOpcao = 5;

DELETE FROM ComentarioVoto
WHERE IdComentario = 2 AND IdVotante = 2;

DELETE FROM Imagem
WHERE IdImagem = 5;