FROM php:8.2-fpm

# Install system dependencies + Node
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install & build assets (if package.json exists)
RUN if [ -f package.json ]; then npm install && npm run build; fi

# Laravel setup
RUN php artisan key:generate && \
    php artisan storage:link || true && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
