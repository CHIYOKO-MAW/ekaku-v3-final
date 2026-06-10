FROM php:8.3-cli

# Install system dependencies required by PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libssl-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configure and install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        gd \
        fileinfo \
        zip \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application source
COPY . .

# Install PHP dependencies (no dev, optimised autoloader)
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Install Node dependencies and build frontend assets
RUN npm ci && npm run build

# Create the storage symlink so uploaded files are publicly accessible
RUN php artisan storage:link || true

# Ensure storage and cache directories are writable
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Run migrations then start the built-in PHP server on the Railway-assigned PORT
CMD php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php -S 0.0.0.0:${PORT:-8000} -t public
