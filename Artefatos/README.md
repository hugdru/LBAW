#### A7 ####


  * (DONE) Remover os indexes em que se junta colunas indevidas
  * (DONE) Index para username e email
  * (DONE) Remover o index do nome
  * (DONE) Pesquisa usando full text search combinação de cenas para a pesquisa
  * (DONE) Indexes através da full text search
  * (DONE) Remover as duas primeiras queries (-- Find "eventos" with a certain "titulo" e -- Find "utilizadores" with a certain username or email) e substituir por full text search.
  * (DONE) Mudar a syntax do wiki para SQL
  * (DONE) Nos triggers de apagar coisas caso outras queiram desaparecer, substituir por cascade
  * Listar os próximos dez eventos:
   * (DONE) com mais participantes
   * (DONE) proximidade (data)
   * popularidade = participantes + proximidade;

##### TODO: #####
  * Indexes nas chaves estrangeiras nas tabelas mais usadas com hash -----> chaves externas no idevento de comentarios, albuns e sondagens
  * Estudo da carga de sistema por tabela, previsão de carga; (nome da relação | tabela) | tamanho (esperado | estimado) | justificação --------> ORDEM DE GRANDEZA EM POTENCIAS DE 10
  * Rever trigger de partcipação/classificação
 

#### A9 ####
  * (DONE) Juntar Módulos 2 e 3
  * Juntar recursos todos do Módulo 4 nma só pag
  * (DONE) Mudar grupo VISITANTE para PUBLICO
  * IMPORTANTE: Acrescentar tabelas para páginas de ação. Cada ação prática no site é composta por uma página de vista (interface) e uma página de ação (sem interface), cada uma delas deve ter uma tabela
  * IMPORTANTE: Ver que ações devem ser feitas por AJAX e acrescentar respetiva resposta JSON (ação de classificar, votar; pedido para saber se há novos comentários, pesquisas)
  * Ver tabela de edição de perfil
