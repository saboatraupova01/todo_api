FROM php:8.3-fpm

WORKDIR /var/www

ENV TZ=Asia/Dushanbe

RUN apt-get update && apt-get install -y \
    tzdata \
    git \
    unzip \
    curl \
    libzip-dev \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && docker-php-ext-install pdo_mysql zip pcntl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
