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
