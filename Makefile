include .env

check: lint psalm
build-production: build-prod-rabbit build-prod-nginx build-prod-php-fpm build-prod-php-workers
push-production: push-prod-rabbit push-prod-nginx push-prod-php-fpm push-prod-php-workers

up:
	docker-compose up -d --build --scale php-cli=0

down:
	docker-compose down --remove-orphans

redis:
	docker exec -it --user www-data $(PROJECT_NAME)-redis redis-cli

php:
	docker exec -it --user www-data $(PROJECT_NAME)-php-fpm bash

php-cli:
	docker exec -it --user www-data $(PROJECT_NAME)-php-cli bash

php-workers:
	docker exec -it --user www-data $(PROJECT_NAME)-php-workers bash

lint:
	docker-compose run --rm php-cli composer lint
	docker-compose run --rm php-cli composer php-cs-fixer fix -- --dry-run --diff

fix:
	docker-compose run --rm php-cli composer php-cs-fixer fix

psalm:
	docker-compose run --rm php-cli composer psalm

composer:
	docker-compose run --rm php-cli composer

test:
	docker-compose run --rm php-cli composer test

test-functional:
	docker-compose run --rm php-cli composer test -- --testsuite=functional

build-prod-nginx:
	docker build -f=app/docker/production/nginx.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-nginx:$(NGINX_IMAGE_TAG) app

build-prod-php-fpm:
	docker build -f=app/docker/production/php-fpm.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-php-fpm:$(PHP_FPM_IMAGE_TAG) app

build-prod-php-workers:
	docker build -f=app/docker/production/php-workers.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-php-workers:$(PHP_WORKERS_IMAGE_TAG) app

build-prod-rabbit:
	docker build -f=app/docker/production/rabbit.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-rabbit:$(RABBITMQ_IMAGE_TAG) app

push-prod-nginx:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-nginx:$(NGINX_IMAGE_TAG)

push-prod-php-fpm:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-php-fpm:$(PHP_FPM_IMAGE_TAG)

push-prod-php-workers:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-php-workers:$(PHP_WORKERS_IMAGE_TAG)

push-prod-rabbit:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-rabbit:$(RABBITMQ_IMAGE_TAG)