#!/bin/sh

set -e

echo "Checking .env..."

if [ ! -f .env ]; then
    cp .env.example .env
fi

echo "Installing Composer dependencies..."

if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist
fi

echo "Waiting for MySQL..."

until php -r "
try {
    new PDO('mysql:host=db;dbname=todo_api', 'user', 'password');
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
"; do
    sleep 2
done

echo "Generating APP_KEY (safe check)..."

if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate --force
fi

echo "Running migrations..."

php artisan migrate --force || true

if [ "$APP_ROLE" = "queue" ]; then
    echo "Starting QUEUE worker..."
    exec php artisan queue:work --tries=3 --verbose
fi

echo "Starting application..."

exec "$@"
