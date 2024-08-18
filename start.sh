#!/bin/bash

# Any setup commands can go here
php artisan migrate --force   # Example: Run database migrations
php-fpm                       # Start the PHP FastCGI Process Manager
