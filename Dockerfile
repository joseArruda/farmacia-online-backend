FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev

RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan storage:link || true

RUN chown -R www-data:www-data storage bootstrap/cache

RUN a2enmod rewrite

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]