# ---- Base PHP image ----
FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all files
COPY . .

# Ensure Composer dependencies install without interaction
RUN composer install --no-interaction --no-progress --no-dev --optimize-autoloader || true

# Build Vite / Filament assets if package.json exists
RUN if [ -f package.json ]; then npm install && npm run build; fi

# Prepare Laravel
RUN php artisan key:generate || true && \
    php artisan storage:link || true && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expose Laravel internal port
EXPOSE 8000

# Run migrations automatically (optional, can remove if not needed)
# RUN php artisan migrate --force || true

# Start Laravel app
CMD php artisan serve --host=0.0.0.0 --port=8000