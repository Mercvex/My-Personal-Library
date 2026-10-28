FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --ignore-platform-reqs --prefer-dist

FROM node:22-slim AS assets
WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm ci && npm run build

FROM dunglas/frankenphp:1-php8.4
RUN install-php-extensions pdo_pgsql mbstring bcmath gd intl zip opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
COPY --from=assets /app/public/build ./public/build
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data storage bootstrap/cache
ENV APP_ENV=production
ENV APP_DEBUG=false
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && frankenphp php-server -r public"]
