#!/bin/bash
set -e

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear optimizations
php artisan optimize:clear

# Start PHP-FPM
php-fpm
