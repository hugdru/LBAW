## Project structure ##
Folder | info             
-----------------|------------------
src | ficheiros php.
resources | nossos ficheiros de javascript, css, scss, etc, nas respectivas pastas.
public | bibliotecas e resources: compilados, minimizados e concatenados. Que são "importadas" no php.
bower_components | resultado de correr bower install, no nosso caso vai buscar bootstrap e dependências.
node_modules | resultado de correr npm install, instala as ferramentas.
bower.json | dados para o bower.
package.json | dados para o npm.
gulpfile.js | conjunto de tarefas automáticas para compilar, limpar, etc.


## Tooling setup ##

### Ubuntu and Intellij ###
1. Registar uma conta em https://www.jetbrains.com/student/ com o email da feup e sacar o intellij idea ultimate(trial) e introduzir o login e password.
2. Instalar os plugins do intellij: php e nodejs
3. sudo apt-get install nodejs npm
4. Criar um projeto de php com o root em projeto e selecionar a versão 5.4 e se possível selecionar o interpretador
5. Dentro do projeto carregar com o botão direito em package.json e selecionar npm install
6. Carregar com o botão direito em gulpfile.js e selecionar a opção Show Gulp Tasks.
7. Selecionar bower na janela de tarefas do gulp
8. Tarefas principais do gulp: clean -> limpa a pasta public, default -> executa o clean; move e compila todos os ficheiros necessários, incremental -> executa o default; e faz watch na pasta resources.
