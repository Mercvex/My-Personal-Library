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
RUN printf 'upload_max_filesize=32M\npost_max_size=40M\nmemory_limit=256M\n' > /usr/local/etc/php/conf.d/uploads.ini
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
COPY --from=assets /app/public/build ./public/build
RUN composer install --no-dev --optimize-autoloader
RUN mkdir -p storage/app/public storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views storage/framework/testing bootstrap/cache \
    && ln -sfn /app/storage/app/public /app/public/storage \
    && chown -R 1000:1000 storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache
ENV XDG_CONFIG_HOME=/tmp/caddy-config
ENV XDG_DATA_HOME=/tmp/caddy-data
ENV APP_ENV=production
ENV APP_DEBUG=false
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && frankenphp php-server -r public --listen :8080"]
