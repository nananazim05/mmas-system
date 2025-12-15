FROM php:8.2-apache

# 1. Install dependency (termasuk PostgreSQL & Library Gambar)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libpng-dev libjpeg62-turbo-dev \
    libfreetype6-dev libicu-dev libonig-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Setup Extension PHP (PostgreSQL, GD, dll)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql zip gd intl bcmath mbstring exif pcntl

# 3. Hidupkan mod Rewrite
RUN a2enmod rewrite

# 4. Set folder kerja
WORKDIR /var/www/html

# 5. Copy fail projek
COPY . .

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Tukar permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Setup Apache Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 🔥 9. INI YANG PENTING: Izinkan .htaccess berfungsi (AllowOverride All)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 10. Setup Port Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf