.DEFAULT_GOAL := help

# Docker
COMPOSE = docker-compose

# Variables
IMAGE_NAME = doctor-management-laravel
CONTAINER_NAME = doctor-management-laravel-app

# Targets
build: ## Build the Docker image
	@echo "Building Docker image..."
	@$(COMPOSE) build

up: ## Start the application
	@echo "Starting application..."
	@$(COMPOSE) up -d

dowN: ## Stop the application
	@echo "Stopping application..."
	@$(COMPOSE) down

fresh: down build up ## Rebuild and restart the application

shell: ## Get a shell into the running container
	@echo "Getting a shell into the container..."
	@$(COMPOSE) exec app bash

test: ## Run the tests
	@echo "Running tests..."
	@$(COMPOSE) exec app php artisan test

migrate: ## Run migrations
	@echo "Running migrations..."
	@$(COMPOSE) exec app php artisan migrate

seed: ## Seed the database
	@echo "Seeding the database..."
	@$(COMPOSE) exec app php artisan db:seed

fresh-db: ## Run migrate:fresh and db:seed
	@echo "Refreshing database..."
	@$(COMPOSE) exec app php artisan migrate:fresh --seed

clean: ## Clean up the environment
	@echo "Cleaning up..."
	@$(COMPOSE) down --remove-orphans

help: ## Display this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

.PHONY: build up down fresh shell test migrate seed fresh-db help
