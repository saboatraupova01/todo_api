#!/bin/sh
set -e

echo "Fixing permissions..."



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
    new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
"; do
    sleep 2
done

echo "Checking APP_KEY..."

if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
else
    echo "APP_KEY already exists."
fi

echo "Running migrations..."

php artisan migrate --force

if [ "$APP_ROLE" = "app" ]; then
    echo "Generating Swagger documentation..."
    php artisan l5-swagger:generate || true
fi

echo "Checking Passport..."

if [ ! -f storage/oauth-private.key ]; then
    echo "Generating Passport keys..."
    php artisan passport:keys
else
    echo "Passport keys already exist."
fi

CLIENT_EXISTS=$(php artisan tinker --execute="echo DB::table('oauth_clients')->where('personal_access_client', true)->count();" 2>/dev/null || echo "0")

if [ "$CLIENT_EXISTS" = "0" ]; then
    echo "Creating Personal Access Client..."
    php artisan passport:client --personal --name="TODO_API" --no-interaction
else
    echo "Personal Access Client already exists."
fi

echo "Starting application..."

exec "$@"
