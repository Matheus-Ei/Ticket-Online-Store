# First stage: Composer
FROM composer:2 as builder

WORKDIR /app

COPY composer.json ./

# RUN composer install --no-dev --no-scripts --no-autoloader
RUN composer install --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

# Second stage: PHP with Apache
FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=builder /app/vendor/ /var/www/html/vendor/

COPY --from=builder /app/ .

RUN sed -i -e 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html
