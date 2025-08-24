#!/bin/sh

# Exit immediately if a command exits with a non-zero status.

# Exit immediately if a command exits with a non-zero status.
set -e

# Change to Laravel project directory
cd /var/www/html

# Debug information
#echo "=== Starting Laravel startup script ==="
#echo "Current directory: $(pwd)"
#ls -la /var/www/html/
#echo "Database file info:"
#ls -la /var/www/html/database/

# Ensure the SQLite database exists
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database/database.sqlite

#echo "Running Laravel startup script..."

#echo "Forcibly creating .env file and generating key..."
#cp /var/www/html/.env.example /var/www/html/.env
#php /var/www/html/artisan key:generate

# Run database migrations
echo "Running database migrations..."
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

echo "Startup script finished."
