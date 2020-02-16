# terry-api-show
This is a show case for https://github.com/simon-schubert/terry-api

## Development setup
1. copy docker/php-fpm/.env.dist to docker/php-fpm/.env and adjust to your needs
1. pull latest image(s): docker-compose pull
1. build the image(s): docker-compose build
1. create the container(s): docker-compose up -d
1. to setup run from php-fpm: composer dev