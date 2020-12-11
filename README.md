# About
This is a show case for https://github.com/violines/rest-bundle

## Development setup
1. copy docker/dev/.env.dist to docker/dev/.env and adjust to your needs
1. pull latest image(s): docker-compose pull
1. build the image(s): docker-compose build
1. create the container(s): docker-compose up -d
1. to setup run from dev: composer dev

## Run test suite
composer test