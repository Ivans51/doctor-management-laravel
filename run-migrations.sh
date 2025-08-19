#!/bin/sh
touch /tmp/script_was_run

# Exit immediately if a command exits with a non-zero status.
set -e

# Debug information
echo "=== Starting Laravel startup script ==="
echo "Current directory: $(pwd)"
ls -la /var/www/html/
echo "Database file info:"
ls -la /var/www/html/database/

# Ensure the SQLite database exists
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database/database.sqlite

echo "Running Laravel startup script..."

echo "Forcibly creating .env file and generating key..."
cp /var/www/html/.env.example /var/www/html/.env
php /var/www/html/artisan key:generate

# Run database migrations
echo "Running database migrations..."
php /var/www/html/artisan config:clear
php /var/www/html/artisan cache:clear
php /var/www/html/artisan migrate --force

# Create storage link
php /var/www/html/artisan storage:link

# Cache configuration
php /var/www/html/artisan config:cache

# Cache routes
php /var/www/html/artisan route:cache

echo "Startup script finished."
