FROM richarvey/nginx-php-fpm:3.1.6

# Copy semua fail projek ke dalam Docker
COPY . .

# Setting wajib untuk Render
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Setting Laravel
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Benarkan Composer run
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install dependencies masa build
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear
RUN npm install
RUN npm run build

CMD ["/start.sh"]