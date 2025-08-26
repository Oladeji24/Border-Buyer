FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions for database
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Copy entrypoint script and set permissions
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && npm run build

# Expose port
EXPOSE 10000

# Use entrypoint script to run migrations and start server
CMD ["/entrypoint.sh"]
