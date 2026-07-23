FROM php:8.3-fpm
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    librdkafka-dev \
    nodejs \
    npm \
    default-mysql-client \
    && pecl install rdkafka redis \
    && docker-php-ext-enable rdkafka redis \
    && docker-php-ext-install pdo_mysql zip pcntl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN chmod +x docker/php/entrypoint.sh

ENTRYPOINT ["docker/php/entrypoint.sh"]

CMD ["php-fpm"]
