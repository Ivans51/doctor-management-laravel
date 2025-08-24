.DEFAULT_GOAL := help

# Variables
IMAGE_NAME = doctor-management-laravel
CONTAINER_NAME = doctor-management-laravel-app
NETWORK_NAME = doctor-management

# Targets
build: ## Build the Docker image
	@echo "Building Docker image..."
	@docker build -t $(IMAGE_NAME) .

up: ## Start the application
	@echo "Starting application..."
	@docker network create $(NETWORK_NAME) || true
	@docker run -d --name $(CONTAINER_NAME) --restart unless-stopped -w /var/www/html -p 8001:80 -v ./:/var/www/html --network $(NETWORK_NAME) -e APP_ENV=local -e APP_DEBUG=true -e APP_URL=http://localhost:8001 $(IMAGE_NAME)

down: ## Stop the application
	@echo "Stopping application..."
	@docker stop $(CONTAINER_NAME) || true
	@docker rm $(CONTAINER_NAME) || true

fresh: down build up ## Rebuild and restart the application

shell: ## Get a shell into the running container
	@echo "Getting a shell into the container..."
	@docker exec -it $(CONTAINER_NAME) bash

test: ## Run the tests
	@echo "Running tests..."
	@docker exec -T -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: $(CONTAINER_NAME) php artisan migrate --force
	@docker exec -T -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: $(CONTAINER_NAME) ./vendor/bin/phpunit

show-env: ## Show the APP_ENV value
	@echo "Showing APP_ENV..."
	@docker exec -T -e APP_ENV=testing $(CONTAINER_NAME) php artisan tinker --execute="echo env('APP_ENV')"

list-vendor: ## List vendor binaries
	@echo "Listing vendor binaries..."
	@docker exec -T $(CONTAINER_NAME) ls -la vendor/bin

composer-install: ## Run composer install
	@echo "Running composer install..."
	@docker exec -T $(CONTAINER_NAME) composer install

clear-cache: ## Clear the cache
	@echo "Clearing cache..."
	@docker exec -T $(CONTAINER_NAME) php artisan config:clear
	@docker exec -T $(CONTAINER_NAME) php artisan cache:clear

migrate: ## Run migrations
	@echo "Running migrations..."
	@docker exec -it $(CONTAINER_NAME) php artisan migrate

seed: ## Seed the database
	@echo "Seeding the database..."
	@docker exec -it $(CONTAINER_NAME) php artisan db:seed

fresh-db: ## Run migrate:fresh and db:seed
	@echo "Refreshing database..."
	@docker exec -it $(CONTAINER_NAME) php artisan migrate:fresh --seed

clean: ## Clean up the environment
	@echo "Cleaning up..."
	@docker stop $(CONTAINER_NAME) && docker rm $(CONTAINER_NAME)
	@docker network rm $(NETWORK_NAME)

logs: ## Show the application logs
	@echo "Showing application logs..."
	@docker logs -f $(CONTAINER_NAME)

help: ## Display this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\u001b[36m%-20s\u001b[0m %s\n", $$1, $$2}'

.PHONY: build up down fresh shell test migrate seed fresh-db help logs