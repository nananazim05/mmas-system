# Guna image PHP 8.2 dengan Apache
FROM php:8.2-apache

# 1. Install dependency (PostgreSQL, Gambar, Zip, dll)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev zip unzip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Setup Extension PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql zip gd intl bcmath mbstring exif pcntl

# 3. Hidupkan mod Rewrite
RUN a2enmod rewrite

# 4. Config Apache DocumentRoot ke folder PUBLIC
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. FIX 404 & PERMISSION (Cara paling selamat: Tambah config terus ke fail utama)
RUN echo "\n<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n" >> /etc/apache2/apache2.conf

# 6. Set folder kerja
WORKDIR /var/www/html

# 7. Copy fail projek
COPY . .

# 8. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 9. Tukar permission folder
RUN chown -R www-data:www-data storage bootstrap/cache

# 10. SETUP PORT MASA RUNTIME (Ini Rahsia Dia!)
CMD sed -i "s/80/$PORT/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf && docker-php-entrypoint apache2-foreground