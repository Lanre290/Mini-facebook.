FROM richarvey/nginx-php-fpm:1.7.2

# Set the working directory to the Laravel application root
WORKDIR /var/www/html

# Copy application files
COPY . .

# Environment configuration
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel-specific environment variables
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1
# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set memory limit for Composer (remove the limit with -1)
ENV COMPOSER_MEMORY_LIMIT=-1

# Clear Composer cache
RUN composer clear-cache

# Install PHP dependencies without dev packages
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# # Set permissions for Laravel storage and cache directories
# RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and make the start script executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start the application using the start script
CMD ["/start.sh"]
