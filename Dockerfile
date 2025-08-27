# Use the official PHP image as a base image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y     git     curl     libpng-dev     libonig-dev     libxml2-dev     zip     unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . .

# Copy existing application directory permissions
RUN chown -R www-data:www-data     /var/www/html/storage     /var/www/html/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts

# Create entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Set entrypoint script
CMD ["/entrypoint.sh"]

