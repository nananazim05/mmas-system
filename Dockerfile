FROM php:8.2-apache

# 1. Install dependency Linux yang diperlukan (termasuk library untuk gambar & bahasa)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Setup Extension PHP (GD, Intl, Zip, MySQL, dll)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip gd intl bcmath mbstring exif pcntl

# 3. Hidupkan mod Rewrite Apache
RUN a2enmod rewrite

# 4. Set folder kerja
WORKDIR /var/www/html

# 5. Copy fail projek
COPY . .

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Tukar permission folder storage (PENTING)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Setup Apache Document Root ke folder PUBLIC
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 9. Setup Port untuk Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf