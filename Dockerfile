FROM php:8.2-apache

# Install barang keperluan asas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Hidupkan mod Rewrite (Supaya tak keluar 404 bila klik link)
RUN a2enmod rewrite

# Set folder kerja dalam server
WORKDIR /var/www/html

# Copy semua fail dari laptop ke server
COPY . .

# Install Composer (Manager untuk PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Tukar permission supaya Laravel boleh tulis fail cache/log
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ubah setting Apache supaya baca folder PUBLIC
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Buka Port 80 (Standard web)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf