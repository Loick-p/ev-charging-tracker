DOCKER_COMPOSE_DEV = docker compose -f docker-compose.yml
DOCKER_COMPOSE_PROD = docker compose -f docker-compose.prod.yml

##
## DEV
##

dev-start:
	$(DOCKER_COMPOSE_DEV) up -d

dev-stop:
	$(DOCKER_COMPOSE_DEV) down

##
## PRO
##

prod-build:
	$(DOCKER_COMPOSE_PROD) build --no-cache

prod-up:
	$(DOCKER_COMPOSE_PROD) up -d

prod-down:
	$(DOCKER_COMPOSE_PROD) down

prod-restart:
	$(DOCKER_COMPOSE_PROD) restart

prod-logs:
	$(DOCKER_COMPOSE_PROD) logs -f

prod-logs-app:
	$(DOCKER_COMPOSE_PROD) logs -f app

prod-logs-db:
	$(DOCKER_COMPOSE_PROD) logs -f db

prod-deploy:
	$(DOCKER_COMPOSE_PROD) build
	$(DOCKER_COMPOSE_PROD) up -d
