# Build stage
FROM composer:2.6.5 as build
WORKDIR /app
COPY . /app
RUN composer install 
RUN php artisan migrate

# Production stage
FROM php:8.2-apache
RUN apt-get update && apt-get install -y libgrpc-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=build /app /var/www/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env.example /var/www/.env
RUN chmod 777 -R /var/www/storage/ && \
    echo "Listen 8080" >> /etc/apache2/ports.conf && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite

EXPOSE 8080
