#### A9 ####
  * (DONE) Juntar Módulos 2 e 3
  * Juntar recursos todos do Módulo 4 nma só pag
  * (DONE) Mudar grupo VISITANTE para PUBLICO
  * (DONE) IMPORTANTE: Acrescentar tabelas para páginas de ação. Cada ação prática no site é composta por uma página de vista (interface) e uma página de ação (sem interface), cada uma delas deve ter uma tabela
  * (DONE) IMPORTANTE: Ver que ações devem ser feitas por AJAX e acrescentar respetiva resposta JSON (ação de classificar, votar; pedido para saber se há novos comentários, pesquisas)
  * (DONE) Ver tabela de edição de perfil

##### TODO (A7): #####
  * Indexes nas chaves estrangeiras nas tabelas mais usadas com hash -----> chaves externas no idevento de comentarios, albuns e sondagens
  * Estudo da carga de sistema por tabela, previsão de carga; (nome da relação | tabela) | tamanho (esperado | estimado) | justificação --------> ORDEM DE GRANDEZA EM POTENCIAS DE 10
  * Rever trigger de partcipação/classificação
 
### TODO: ###

#### A10 ####
 * Implementar o upload/visualização da foto de utilizador
 * Implementar a edição de perfil
 * Pôr tabelas das User Stories implementadas na wiki

#### A11 ####
* Cada instrução SQL [SQLXXX] deve ser representada por uma tabela:
** 1 - Nome. Ex: SQL101 - Login
** 2 - Descrição
** 3 - Código/Instrução/transação SQL
** 4 - Referência para o recurso que utiliza a instrução/transação.
* TRANSAÇÕES: são conjuntos de instruções SQL que só fazem sentido como um todo. Não podem ser interrompidas a meio.
* Identificar as transações necessárias para o site e explorar isso exaustivamente
* Identificar o nível de isolamento para cada ação
