-- Optimize search by "evento titulo" because it is used to list and search for events.
CREATE INDEX CONCURRENTLY evento_titulo_index ON Evento(titulo);

-- Alternative to INDEX on all "evento" rows, depending on being public or not. (Would have to analyze the users trend)
CREATE INDEX CONCURRENTLY evento_titulo_index ON Evento(titulo) WHERE publico is TRUE;

-- Optimize search by "utilizador username" because it is used in the: search of events, "utilizadores", search of "anfitrioes"; login. Every search box where username fits email also fits.
CREATE UNIQUE INDEX CONCURRENTLY utilizador_nomeUsernameEmail_index ON Utilizador(nome, username, email);
