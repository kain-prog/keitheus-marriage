# Criar container
docker build -t internit:bi_leads .

# Excluir Todos os Containers:
docker rm -f $(docker ps -a -q)

# Excluir Todas as Imagens:
docker rmi -f $(docker images -q)

# Excluir Todos os Volumes Não Utilizados:
docker volume prune -f

# Listar os containers rodando
docker ps

# Rodar o COMPOSER dentro do container
docker exec -it sistemaunicointegracaoback_php83_1 composer update -vvv --working-dir=/var/www/html

# Criar projeto numa pasta temporária e depois remover
composer create-project symfony/skeleton temp
mv temp/* temp/.* . 2>/dev/null || true
rm -rf temp
