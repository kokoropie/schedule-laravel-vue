# ============================================================
# Stage 1: Builder
# ============================================================
FROM php:8.3-cli AS builder

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update && apt-get install -y --no-install-recommends \
    unzip libzip-dev libonig-dev libxml2-dev libpq-dev libsqlite3-dev nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY package.json package-lock.json ./
RUN npm ci --prefer-offline

COPY . .
RUN composer run-script post-autoload-dump --no-interaction 2>/dev/null || true
RUN npm run build

# ============================================================
# Stage 2: Production (Zeabur-compatible)
# ============================================================
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev libonig-dev libxml2-dev libpq-dev libsqlite3-dev \
    nginx gettext-base supervisor default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite pgsql mbstring zip pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/pear

# Định nghĩa log_format cho Nginx để đo thời gian
RUN echo 'log_format timed_combined '\''$remote_addr - $remote_user [$time_local] "$request" $status $body_bytes_sent "$http_referer" "$http_user_agent" rt=$request_time urt=$upstream_response_time'\'';' > /etc/nginx/conf.d/log_format.conf

WORKDIR /var/www

COPY . .
COPY --from=builder /app/vendor ./vendor
COPY --from=builder /app/public/build ./public/build

RUN rm -rf .env.example .git tests node_modules docker README.md phpunit.xml vite.config.*

RUN chown -R www-data:www-data storage bootstrap
RUN chmod -R 775 storage bootstrap

RUN php artisan optimize \
    && php artisan storage:link

# nginx.conf dùng biến $PORT — Zeabur inject lúc container start
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/web/ /etc/supervisor/conf.d/
COPY docker/php/ /usr/local/etc/php/conf.d/

COPY docker/start-web.sh /start-web.sh
RUN chmod +x /start-web.sh

# EXPOSE dùng biến — Zeabur đọc PORT env để route traffic
EXPOSE 8080 

CMD ["/start-web.sh"]