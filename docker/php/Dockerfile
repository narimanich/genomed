FROM php:8.1-fpm-alpine

WORKDIR /var/www/html
RUN docker-php-ext-install pdo pdo_mysql opcache mysqli
RUN apk add --no-cache \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        freetype-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd
RUN apk add --no-cache \
    libcurl \
    curl-dev \
    && docker-php-ext-install curl \
    && docker-php-ext-enable curl

COPY app .

RUN curl -sS https://getcomposer.org/installer  | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD ["php-fpm"]

