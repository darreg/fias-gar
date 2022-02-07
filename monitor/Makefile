include .env

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

caddy-password:
	docker run --rm caddy caddy hash-password --plaintext $(password)

check-alert-rules:
	docker exec -it monitor-prometheus promtool check rules /etc/prometheus/alerts/rules.yml

