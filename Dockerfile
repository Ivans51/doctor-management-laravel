# ---- Stage 1: Build PHP Dependencies ----
# Use the official PHP image with composer
FROM composer:2 as vendor

WORKDIR /app
# Copy only composer files to leverage Docker cache
COPY database/ database/
COPY composer.json composer.lock ./

# Install dependencies for production
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader


# ---- Stage 2: Build Frontend Assets ----
# Use the official Node image
FROM node:18 as frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build


# ---- Stage 3: Final Production Image ----
# Use the richarvey image you started with
FROM richarvey/nginx-php-fpm:3.1.6

# Set Laravel-specific environment variables
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV LOG_CHANNEL stderr

# Copy the application code (without vendor or node_modules)
COPY . /var/www/html

# Copy the built dependencies from the previous stages
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set correct permissions for storage and cache
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# The base image's default CMD will run /start.sh, which starts nginx and php-fpm.
# We no longer need to override it.
