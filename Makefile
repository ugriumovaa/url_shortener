.DEFAULT_GOAL := help

COMPOSE   := docker compose
APP       := $(COMPOSE) exec -T app
APP_RUN   := $(COMPOSE) run --rm app
APP_URL   := http://localhost:8076

.PHONY: help up down restart build rebuild logs ps env env-docker setup install key wait-db migrate fresh seed test cache-clear optimize shell bash composer artisan pint clean

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-18s\033[0m %s\n", $$1, $$2}'

# Docker

up: ## Start containers
	$(COMPOSE) up -d

down: ## Stop containers
	$(COMPOSE) down

restart: down up ## Restart containers

build: ## Build images
	$(COMPOSE) build --pull

rebuild: ## Rebuild images without cache
	$(COMPOSE) build --no-cache --pull

logs: ## Follow logs
	$(COMPOSE) logs -f

ps: ## Show container status
	$(COMPOSE) ps

clean: ## Stop containers and remove volumes
	$(COMPOSE) down -v

# Environment

env: ## Create .env from .env.example
	@test -f .env || cp .env.example .env

setup: build up install key wait-db migrate ## First-time setup
	@echo "Done. Open $(APP_URL)"

install: ## Install composer
	$(APP_RUN) composer install --no-interaction --prefer-dist

key: ## Generate APP_KEY
	$(APP) php artisan key:generate --force

wait-db: ## Wait until MySQL is ready
	@$(COMPOSE) exec -T db bash -c 'until mysqladmin ping -h localhost -uroot -proot --silent 2>/dev/null; do sleep 1; done'

# Laravel

migrate: wait-db ## Run migrations
	$(APP) php artisan migrate --force

fresh: wait-db ## Drop tables and migrate
	$(APP) php artisan migrate:fresh --force

cache-clear: ## Clear Laravel caches
	$(APP) php artisan optimize:clear

optimize: ## Cache config, routes and views
	$(APP) php artisan optimize

# Shell access

shell: bash ## Open shell in app container

bash: ## Open bash in app container
	$(COMPOSE) exec app bash

composer: ## Run composer (make composer c="require pkg/name")
	$(APP) composer $(c)

artisan: ## Run artisan (make artisan c="route:list")
	$(APP) php artisan $(c)

# Code style

pint: ## Fix PHP code style
	$(APP) vendor/bin/pint
