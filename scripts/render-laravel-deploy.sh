#!/usr/bin/env bash
echo "Copy .env"
php -r "file_exists('.env') || copy('.env.example', '.env');"

echo "Install Dependencies new"
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

echo "Directory Permissions"
chmod -R 777 storage bootstrap/cache

echo "Caching config..."
php artisan optimize

echo "Running migrations..."
php artisan migrate:fresh --seed --force

echo "Link files"
php artisan storage:link

echo "Install front-end dependencies"
npm install
npm run build

echo "Run test"
#php artisan test --exclude-group ignore
