FROM php:8.1-apache-bullseye

RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

# RUN cd /tmp && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

RUN cd /tmp && php composer-setup.php

RUN cd /tmp && php -r "unlink('composer-setup.php');"
RUN mv /tmp/composer.phar /usr/local/bin/composer

RUN composer --global config process-timeout 3000

RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash -

RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev \
    zip \
    sudo \
    unzip \ 
    libmagickwand-dev\ 
    libzip-dev

RUN docker-php-ext-install pdo_mysql mysqli iconv mbstring exif zip

RUN unlink /etc/localtime && \
    ln -s /usr/share/zoneinfo/Asia/Jakarta /etc/localtime

WORKDIR /var/www/html

RUN a2enmod rewrite