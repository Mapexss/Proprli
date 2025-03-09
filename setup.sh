#!/bin/bash

docker compose up --build -d

set -e

docker exec -it app bash -c "cd /var/www/html/app"


docker exec -it app bash -c "composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev"
docker exec -it app bash -c "composer run post-root-package-install "

docker exec -it app bash -c "php artisan key:generate --force"
docker exec -it app bash -c "php artisan migrate:fresh --force"

#docker exec -it app bash -c "php artisan config:cache"
#docker exec -it app bash -c "php artisan event:cache"
#docker exec -it app bash -c "php artisan route:cache"
#docker exec -it app bash -c "php artisan view:cache"
