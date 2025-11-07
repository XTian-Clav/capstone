# ---- PHP base image ----
FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build Vite / Filament assets
RUN npm install && npm run build

# Prepare Laravel app
RUN php artisan key:generate && \
    php artisan storage:link && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expose Laravel's internal port
EXPOSE 8000

# Start the app directly (no Nginx needed)
CMD php artisan serve --host=0.0.0.0 --port=8000
