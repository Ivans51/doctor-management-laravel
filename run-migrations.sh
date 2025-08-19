#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

echo "Running Laravel startup script..."

# Run database migrations
php /var/www/html/artisan migrate --force

# Create storage link
php /var/www/html/artisan storage:link

# Cache configuration
php /var/www/html/artisan config:cache

# Cache routes
php /var/www/html/artisan route:cache

echo "Startup script finished."
