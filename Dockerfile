FROM richarvey/nginx-php-fpm:1.7.2

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mbstring

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT=-1

# Clear Composer cache and install dependencies
RUN composer clear-cache && composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist -vvv

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
