include .env

DOCKERHOST = $(shell ifconfig | grep -A3 docker | grep -Eo "[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}" | head -n1)


.PHONY: start stop redis php

start:
	@bash -c "if [ ! -f \"$(PHPINIFILE)\" ]; then cp \"$(PHPINIFILE).dist\" \"$(PHPINIFILE)\"; fi"
	@bash -c "if [ ! -f \"$(XDEBUGINIFILE)\" ]; then cp \"$(XDEBUGINIFILE).dist\" \"$(XDEBUGINIFILE)\"; fi"
	DOCKERHOST=$(DOCKERHOST) docker-compose up -d --build

stop:
	DOCKERHOST=$(DOCKERHOST) docker-compose down

redis:
	docker exec -it --user www-data $(PROJECT_NAME)-redis redis-cli

php:
	docker exec -it --user www-data $(PROJECT_NAME)-php-fpm bash