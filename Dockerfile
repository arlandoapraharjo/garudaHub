FROM php:8.3-cli-bookworm

# Install system dependencies and PHP extensions required by Laravel & SQLite/PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    libpq-dev \
    && docker-php-ext-install zip pdo_sqlite pdo_pgsql bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Hide X-Powered-By header in PHP runtime
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini

# Copy Composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first to maximize Docker build cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy application source
COPY . .

# Generate optimized autoloader and prepare writable directories
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

# Copy startup script and normalize line endings for Linux container execution
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
