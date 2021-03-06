#!/bin/bash

cp .env.dist .env
source .env
export MYSQL_PORT
docker-compose build
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php /app/scripts/wait-for-it.sh mysql:$MYSQL_PORT -- echo "mysql is up"
docker-compose exec php php bin/console doctrine:database:create
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction