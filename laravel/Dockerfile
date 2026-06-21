FROM node:20-alpine AS frontend-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/
RUN npm run build


FROM php:8.3-fpm

# Установка необходимых системных библиотек
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libssl-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем PHP-расширения
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Redis через PECL
RUN pecl install redis && docker-php-ext-enable redis

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js 20 для Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# Создаём пользователя (как в рабочем проекте)
RUN useradd -G www-data,root -u 1000 -d /home/www www \
    && mkdir -p /home/www/.composer \
    && chown -R www:www /home/www

# Копируем файлы и выставляем права ДО переключения пользователя
COPY . .
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

# Права на storage и cache — владелец + разрешения
RUN chown -R www:www /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER www

EXPOSE 9000
CMD ["php-fpm"]