FROM richarvey/nginx-php-fpm:3.1.6

# 1. Install Node.js & NPM
RUN apk add --no-cache nodejs npm

COPY . .

# Setting Server
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Setting Laravel
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Benarkan Composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear
RUN php artisan storage:link

# 2. Build Assets 
RUN npm install
RUN npm run build

# 3. Auto Migrate & Start
CMD php artisan migrate --force && /start.sh