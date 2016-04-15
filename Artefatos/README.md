##### A7 #####

  * Estudo da carga de sistema por tabela, previsão de carga; (nome da relação | tabela) | tamanho (esperado | estimado) | justificação
  * Remover os indexes em que se junta colunas indevidas
  * Index para username e email
  * Remover o index do nome
  * Pesquisa usando full text search combinação de cenas para a pesquisa
  * Indexes através da full text search
  * (DONE) Remover as duas primeiras queries (-- Find "eventos" with a certain "titulo" e -- Find "utilizadores" with a certain username or email) e substituir por full text search.
  * Listar os próximos dez eventos: com mais participantes; proximidade (data) (DONE); popularidade = participantes + proximidade;
  * Indexes nas chaves estrangeiras nas tabelas mais usadas
  * (DONE) Mudar a syntax do wiki para SQL
  * (DONE) Nos triggers de apagar coisas caso outras queiram desaparecer, substituir por cascade

##### A8 #####
  * Tudo exceto os inserts num ficheiro
  * Inserts noutro ficheiro
  * (DONE) Mais inserts por cada tabela cerca de dez (poderá depender da dimensão da tabela)
