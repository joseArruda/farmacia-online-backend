FROM php:8.2-apache

# dependências
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN a2enmod rewrite

# 🔥 REMOVE SITE PADRÃO DO APACHE
RUN a2dissite 000-default.conf

# 🔥 CRIA SITE NOVO APONTANDO DIRETO PARA /public
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/laravel.conf

RUN a2ensite laravel.conf

# permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan storage:link || true

EXPOSE 80

CMD ["apache2-foreground"]