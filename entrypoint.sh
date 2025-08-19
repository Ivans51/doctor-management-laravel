#!/bin/sh
/etc/cont-init.d/01-laravel-startup
exec docker-php-entrypoint /start.sh