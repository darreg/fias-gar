include .env

check: lint phpcs psalm
fix: phpcbf

start:
	docker-compose up -d --build

stop:
	docker-compose down --remove-orphans

redis:
	docker exec -it --user www-data $(PROJECT_NAME)-redis redis-cli

php:
	docker exec -it --user www-data $(PROJECT_NAME)-php-fpm bash

php-cli:
	docker exec -it --user www-data $(PROJECT_NAME)-php-cli bash

lint:
	docker-compose run --rm php-cli composer lint

phpcs:
	docker-compose run --rm php-cli composer phpcs

phpcbf:
	docker-compose run --rm php-cli composer phpcbf

psalm:
	docker-compose run --rm php-cli composer psalm