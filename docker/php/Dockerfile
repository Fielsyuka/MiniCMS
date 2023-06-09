FROM php:7.2-apache
COPY php.ini /usr/local/etc/php/
RUN apt-get update  \
  && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmcrypt-dev \
  && docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd exif
