FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia o projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ativa mod_rewrite
RUN a2enmod rewrite

# Define a pasta public como DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Permite o uso do .htaccess
RUN echo '<Directory /var/www/html/public>\n\
AllowOverride All\n\
Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Permissões
RUN mkdir -p storage/framework/{sessions,views,cache}

RUN chown -R www-data:www-data storage bootstrap/cache

# Link do storage
RUN php artisan storage:link || true

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf

RUN sed -i 's!/var/www/!/var/www/html/public/!g' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]