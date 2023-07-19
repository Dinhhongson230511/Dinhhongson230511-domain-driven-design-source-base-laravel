FROM php:8.0-fpm-alpine

WORKDIR /var/www

RUN apk update
RUN apk add --no-cache \
libjpeg-turbo-dev \
libpng-dev \
libwebp-dev \
freetype-dev \
libzip-dev \
zip \
bash 


RUN docker-php-ext-install pdo pdo_mysql 
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install zip

COPY . .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN cd /var/www && composer install --ignore-platform-reqs --no-scripts 
RUN cd /var/www && php artisan key:generate


CMD ["php-fpm"]