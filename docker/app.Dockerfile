FROM php:8.3-fpm
RUN apt-get update && apt-get install -y git zip unzip libpng-dev libjpeg-dev libonig-dev libxml2-dev libzip-dev
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist || true
RUN php artisan key:generate || true
EXPOSE 9000
CMD ["php-fpm"]
