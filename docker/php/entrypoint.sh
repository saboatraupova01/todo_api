#!/bin/sh

set -e

echo "Checking .env..."

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

echo "Installing Composer dependencies..."

if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist
fi

echo "Waiting for MySQL..."

until mysqladmin ping \
          -h"$DB_HOST" \
          -u"$DB_USERNAME" \
          -p"$DB_PASSWORD" \
          --skip-ssl \
          --silent
do
    sleep 2
done

echo "MySQL is ready!"

echo "Checking APP_KEY..."

if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Generating Swagger..."
php artisan l5-swagger:generate || true

echo "Checking Passport keys..."

if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
    php artisan passport:keys --force
fi

echo "Checking Personal Access Client..."

CLIENT_EXISTS=$(php artisan tinker --execute="
echo DB::table('oauth_clients')
    ->where('personal_access_client', true)
    ->exists() ? 1 : 0;
" 2>/dev/null | tr -d '\r\n')

if [ "$CLIENT_EXISTS" != "1" ]; then
    echo "Creating Personal Access Client..."
    php artisan passport:client \
        --personal \
        --name="TODO_API" \
        --no-interaction
fi

echo "Creating storage link..."
php artisan storage:link || true

if [ "$APP_ROLE" = "queue" ]; then
    echo "Starting Queue Worker..."
    exec php artisan queue:work --tries=3 --verbose
fi

if [ "$APP_ROLE" = "reverb" ]; then
    echo "Starting Reverb..."
    exec php artisan reverb:start --host=0.0.0.0 --port=8080
fi

if [ "$APP_ROLE" = "kafka" ]; then
    echo "Starting Kafka Consumer..."
    exec php artisan kafka:consume
fi

echo "Starting PHP-FPM..."
exec php-fpm -F
