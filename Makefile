dev:
	docker compose up -d --build

prod:
	docker compose -f docker-compose.prod.yml up -d --build

stop:
	docker compose down
	docker compose -f docker-compose.prod.yml down
