# Sobre o ViewLog

Sistema que organiza registros de logs. Criado utilizando Laravel Framework versão 5.5.

# Instalação

Renomeie o arquivo .env.exemplo para .env e faça as alterações nas configurações de acordo com sua base de dados.

Execute `php artisan migrate`. 

## Analisando vários arquivos

Crie a pasta viewlog/storage/logs/temp e dentro dela cole as pastas com os arquivos .log que deseja analisar.

Execute a queue que escuta afila de arquivos a serem analisados.
