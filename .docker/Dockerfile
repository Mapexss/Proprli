FROM php:8.2-fpm

WORKDIR /var/www/html/app

COPY --chown=app:app . .

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libpq-dev \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \ 
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    dom \
    xml \
    xmlwriter

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./setup.sh /temp

ENTRYPOINT ["php-fpm"]
