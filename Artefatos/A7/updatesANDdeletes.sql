-- UPDATES	

UPDATE Evento
SET descriçao = 'A festa e fixe'
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
WHERE 

UPDATE Convite
SET resposta = 'TRUE'
WHERE idEvento = 1, idConvidado = 2;


-- DELETES

DELETE FROM Comentario
WHERE IdComentario = 1;

DELETE FROM Opcao
WHERE idOpcao = 10;

DELETE FROM Seguidor
WHERE IdSeguidor = 2, IdSeguido = 1;

DELETE FROM UtilizadorOpcao
WHERE IdUtilizador = 2, IdOpcao = 5;

DELETE FROM ComentarioVoto
WHERE IdComentario = 2, IdVotante = 2;

DELETE FROM Imagem
WHERE IdImagem = 5;