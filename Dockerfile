FROM php:8.2-apache

# Dependências do sistema
RUN apt-get update && apt-get install -y unzip git libzip-dev && rm -rf /var/lib/apt/lists/*

# Extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli zip
RUN pecl install redis && docker-php-ext-enable redis

# PHP upload limits
RUN echo "upload_max_filesize=64M\npost_max_size=64M\nmemory_limit=256M" > /usr/local/etc/php/conf.d/uploads.ini

# Habilitar mod_rewrite
RUN a2enmod rewrite

# DocumentRoot apontando para /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# AllowOverride All para .htaccess
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar projeto
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Criar diretórios e permissões
RUN mkdir -p storage public/uploads && chown -R www-data:www-data storage public/uploads

EXPOSE 80
