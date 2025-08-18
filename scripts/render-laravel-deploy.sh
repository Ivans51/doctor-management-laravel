#!/usr/bin/env bash
echo "Copy .env"
php -r "file_exists('.env') || copy('.env.example', '.env');"

echo "Install Dependencies"
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

echo "Generate key"
php artisan key:generate

# Ensure the SQLite database file exists
touch database/database.sqlite
chown -R nginx:nginx database/

echo "Directory Permissions"
#chmod -R 777 storage bootstrap/cache
chown -R nginx:nginx storage bootstrap/cache

# check permission
#ls -la storage bootstrap/cache

echo "Caching config..."
php artisan optimize:clear
#php artisan cache:clear
#php artisan config:clear
#php artisan view:clear
#php artisan route:clear

echo "Running migrations..."
php artisan migrate:fresh --seed

echo "Link files"
php artisan storage:link

echo "Run test"
#php artisan test --exclude-group ignore
