include .env

build-production: build-prod-caddy build-prod-prometheus build-prod-alertmanager build-prod-grafana
push-production: push-prod-caddy push-prod-prometheus push-prod-alertmanager push-prod-grafana

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

caddy-password:
	docker run --rm caddy caddy hash-password --plaintext $(password)

check-alert-rules:
	docker exec -it monitor-prometheus promtool check rules /prometheus_alerts/rules.yml

prometheus-reload:
	docker exec -it monitor-prometheus killall -HUP prometheus

build-prod-caddy:
	docker build -f=docker/production/caddy.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-caddy:$(CADDY_IMAGE_TAG) docker

build-prod-prometheus:
	docker build -f=docker/production/prometheus.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-prometheus:$(PROMETHEUS_IMAGE_TAG) docker

build-prod-alertmanager:
	docker build -f=docker/production/alertmanager.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-alertmanager:$(ALERTMANAGER_IMAGE_TAG) docker

build-prod-grafana:
	docker build -f=docker/production/grafana.docker -t $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-grafana:$(GRAFANA_IMAGE_TAG) docker


push-prod-caddy:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-caddy:$(CADDY_IMAGE_TAG)

push-prod-prometheus:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-prometheus:$(PROMETHEUS_IMAGE_TAG)

push-prod-alertmanager:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-alertmanager:$(ALERTMANAGER_IMAGE_TAG)

push-prod-grafana:
	docker push $(REGISTRY_ADDRESS)/$(PROJECT_NAME)-grafana:$(GRAFANA_IMAGE_TAG)
