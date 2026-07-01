# Usa uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Habilita o mod_rewrite do Apache (essencial para as rotas do Laravel)
RUN a2enmod rewrite

# Instala dependências do sistema e extensões do PHP necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Copia sua configuração personalizada do Apache
# (Crie este arquivo 000-default.conf na raiz do seu projeto com o conteúdo que você postou)
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Ajusta permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponha a porta 80
EXPOSE 80

# O comando para iniciar o Apache (isso substitui a necessidade de definir no Render)
CMD ["apache2-foreground"]