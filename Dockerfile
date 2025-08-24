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

# Install required packages
RUN apk add --no-cache dos2unix sqlite

# Copy the built dependencies from the previous stages
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

COPY . .

# Copy the script to the correct location and make it executable
RUN mkdir -p /scripts
COPY scripts/00-laravel-deploy.sh /scripts/00-laravel-deploy.sh
RUN chmod +x /scripts/00-laravel-deploy.sh

# Install system dependencies for PHP extensions gd and xsl
#RUN apk update && apk add --no-cache libpng libxslt libjpeg-turbo libjpeg-turbo-dev && rm -rf /var/cache/apk/*

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set database to sqlite
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite

CMD ["/start.sh"]
