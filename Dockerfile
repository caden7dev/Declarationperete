FROM php:7.4-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    git curl unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions pour storage/cache Laravel
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000
CMD php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
