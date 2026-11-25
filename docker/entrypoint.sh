#!/usr/bin/env sh
set -eu

mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache database

if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_PATH")"
    touch "$DB_PATH"
fi

php artisan config:clear
php artisan migrate --force

# Seed sample data automatically on fresh database
if [ "$(php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo \App\Models\User::count();')" = "0" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
