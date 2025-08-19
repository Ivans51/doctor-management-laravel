# ---- Stage 1: Build PHP Dependencies ----
FROM composer:2 as vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader

# ---- Stage 2: Build Frontend Assets ----
FROM node:18 as frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- Stage 3: Final Production Image ----
FROM richarvey/nginx-php-fpm:3.1.6

# Set Laravel-specific environment variables
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV LOG_CHANNEL stderr

# Copy the application code
COPY . /var/www/html

# Copy the built dependencies from the previous stages
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set correct permissions for storage and cache
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ================================================
# NEW LINES ADDED HERE
# Copy the startup script and make it executable
COPY run-migrations.sh /etc/cont-init.d/01-laravel-startup
RUN chmod +x /etc/cont-init.d/01-laravel-startup
# ================================================

# The base image's default CMD will run /start.sh, which starts nginx and php-fpm.
# It will automatically execute our script in /etc/cont-init.d/ before starting the services.
