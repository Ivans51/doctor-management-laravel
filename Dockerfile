FROM richarvey/nginx-php-fpm:2.0.1

# Build information
RUN echo "🔧 Building Laravel API container..."

# Copy application code
COPY . .
RUN echo "✅ Application code copied successfully"

# Display environment info
RUN echo "📦 Environment: ${APP_ENV:-development}"

# Install system dependencies for PHP extensions gd and xsl
#RUN apk update && apk add --no-cache libpng libxslt libjpeg-turbo libjpeg-turbo-dev && rm -rf /var/cache/apk/*

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Final setup message
RUN echo "🚀 Container setup completed successfully!"

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
