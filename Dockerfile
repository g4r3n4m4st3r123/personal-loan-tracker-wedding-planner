# ============================================================
# Stage 1: Build frontend assets with Node 22
# ============================================================

FROM node:22-bookworm AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

RUN npm run build


# ============================================================
# Stage 2: Laravel / PHP
# ============================================================

FROM php:8.4-fpm-bookworm

WORKDIR /var/www/html


# ============================================================
# System dependencies
# ============================================================

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        git \
        unzip \
        curl \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        mbstring \
        bcmath \
        intl \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# ============================================================
# Composer
# ============================================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ============================================================
# Laravel application
# ============================================================

COPY . .


# ============================================================
# Composer dependencies
# ============================================================

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# ============================================================
# Copy built frontend assets
# ============================================================

COPY --from=frontend /app/public/build ./public/build


# ============================================================
# Laravel writable directories
# ============================================================

RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        storage \
        bootstrap/cache


# ============================================================
# Nginx configuration
# ============================================================

COPY docker/nginx.conf \
    /etc/nginx/nginx.conf


# ============================================================
# PHP-FPM configuration
# ============================================================

COPY docker/www.conf \
    /usr/local/etc/php-fpm.d/www.conf


# ============================================================
# Supervisor configuration
# ============================================================

COPY docker/supervisord.conf \
    /etc/supervisor/conf.d/supervisord.conf


# ============================================================
# Laravel startup script
# ============================================================

COPY docker/start.sh \
    /usr/local/bin/start.sh

RUN chmod +x \
    /usr/local/bin/start.sh


# ============================================================
# Render port
# ============================================================

EXPOSE 10000


# ============================================================
# Start
# ============================================================

CMD ["/usr/local/bin/start.sh"]