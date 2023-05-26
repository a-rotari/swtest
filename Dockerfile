FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    nano \
    curl \
    wget \
    git \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libmariadb-dev-compat \
    nodejs \
    npm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip gd

RUN npm install -g less
RUN npm install -g less-watch-compiler

WORKDIR /var/www/html

CMD ["sh", "-c", "less-watch-compiler /var/www/html/public/less /var/www/html/public/css && php-fpm -F"]


EXPOSE 80

