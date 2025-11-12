FROM php:8.3-fpm
RUN apt-get update && apt-get install -y git zip unzip libpng-dev libjpeg-dev libonig-dev libxml2-dev libzip-dev wget
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
# composer install will be run by provision.sh to ensure packages including dompdf are installed
RUN php -v
EXPOSE 9000
CMD ["php-fpm"]
