FROM richarvey/nginx-php-fpm:2.0.1

COPY . .

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

RUN apk add --update nodejs npm

# Copy Uuidable.php to the vendor directory
RUN cp vendor_files/Uuidable.php vendor/your-app-rocks/eloquent-uuid/src/Traits/Uuidable.php

CMD ["/start.sh"]
