
FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions \
    pdo_sqlite \
    pdo_pgsql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force && frankenphp php-server -r public"]
