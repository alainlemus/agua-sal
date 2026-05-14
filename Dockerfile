FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM php:8.4-cli-alpine AS app
WORKDIR /var/www/html

ARG APP_ENV=production
ENV APP_ENV=${APP_ENV}

RUN apk add --no-cache \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    icu-dev \
    unzip \
    git

RUN docker-php-ext-install pdo_mysql bcmath gd zip intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "local" ]; then \
        composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts; \
    else \
        composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts; \
    fi

COPY . .
COPY --from=frontend-builder /app/public/build ./public/build

RUN mkdir -p bootstrap/cache \
    storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views

RUN composer dump-autoload --optimize --no-scripts \
    && php artisan package:discover --ansi

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-lc", "php artisan storage:link || true; php artisan config:cache; php artisan route:cache; php artisan view:cache; php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
