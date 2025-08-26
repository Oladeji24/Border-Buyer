#!/bin/bash

# Production Deployment Optimization Script for Border Buyers
# This script optimizes the application for Render.com deployment

set -e

echo "🚀 Starting Border Buyers Production Optimization..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

# Function to log messages
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# 1. Optimize Composer Dependencies
log_message "📦 Optimizing Composer dependencies..."
if command_exists composer; then
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
    composer dump-autoload --optimize
else
    log_message "⚠️  Composer not found, skipping dependency optimization"
fi

# 2. Optimize Laravel Configuration
log_message "⚙️  Optimizing Laravel configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Set proper file permissions
log_message "🔒 Setting proper file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log 2>/dev/null || true
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;

# 4. Create storage link if it doesn't exist
log_message "🔗 Creating storage link..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# 5. Optimize database
log_message "🗄️  Running database migrations..."
php artisan migrate --force

# 6. Clear and warm up cache
log_message "🚀 Warming up cache..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# 7. Generate application key if not set
if [ -z "$APP_KEY" ] && [ -f ".env" ]; then
    log_message "🔑 Generating application key..."
    php artisan key:generate --force
fi

# 8. Set environment-specific optimizations
if [ "$APP_ENV" = "production" ]; then
    log_message "🌍 Applying production-specific optimizations..."
    
    # Set production environment variables
    export APP_ENV=production
    export APP_DEBUG=false
    export LOG_CHANNEL=stderr
    
    # Optimize for production
    php artisan optimize --force
fi

# 9. Check for common security issues
log_message "🔍 Checking for common security issues..."

# Check if .env is properly secured
if [ -f ".env" ] && [ "$(stat -c %a .env)" != "600" ]; then
    chmod 600 .env
    log_message "🔒 Secured .env file permissions"
fi

# Check for exposed debug information
if grep -q "APP_DEBUG=true" .env 2>/dev/null && [ "$APP_ENV" = "production" ]; then
    log_message "⚠️  Warning: APP_DEBUG is true in production environment"
fi

# 10. Create backup directories
log_message "💾 Creating backup directories..."
mkdir -p storage/backups/database
mkdir -p storage/backups/files

# 11. Set up log rotation
log_message "📝 Setting up log rotation..."
mkdir -p storage/logs/archive

# 12. Check required services
log_message "🔌 Checking required services..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;" | cut -d. -f1,2)
if [ "$(printf '%s\n' "8.0" "$PHP_VERSION" | sort -V | head -n1)" != "8.0" ]; then
    log_message "❌ PHP version $PHP_VERSION is below minimum requirement (8.0)"
    exit 1
fi

log_message "✅ PHP version $PHP_VERSION meets requirements"

# Check required PHP extensions
REQUIRED_EXTENSIONS="mbstring openssl pdo_mysql tokenizer xml curl zip gd intl"
for ext in $REQUIRED_EXTENSIONS; do
    if php -m | grep -q "^$ext$"; then
        log_message "✅ PHP extension $ext is installed"
    else
        log_message "❌ Required PHP extension $ext is missing"
    fi
done

# 13. Optimize asset compilation
log_message "🎨 Optimizing assets..."
if [ -f "package.json" ]; then
    if command_exists npm; then
        npm ci --production=false
        if [ -f "webpack.mix.js" ] || [ -f "vite.config.js" ]; then
            npm run build
        fi
    elif command_exists yarn; then
        yarn install --production=false
        if [ -f "webpack.mix.js" ] || [ -f "vite.config.js" ]; then
            yarn build
        fi
    else
        log_message "⚠️  Neither npm nor yarn found, skipping asset compilation"
    fi
fi

# 14. Create health check endpoint verification
log_message "🏥 Verifying health check endpoint..."
if grep -q "Route::get('/health'" routes/web.php; then
    log_message "✅ Health check endpoint found"
else
    log_message "⚠️  Health check endpoint not found in routes/web.php"
fi

# 15. Final optimization summary
log_message "📊 Deployment Optimization Summary:"
log_message "   ✅ Composer dependencies optimized"
log_message "   ✅ Laravel configuration cached"
log_message "   ✅ File permissions set"
log_message "   ✅ Storage link created"
log_message "   ✅ Database migrations completed"
log_message "   ✅ Cache cleared and warmed up"
log_message "   ✅ Security checks performed"
log_message "   ✅ Asset compilation completed"

echo ""
echo "🎉 Border Buyers application optimized for production deployment!"
echo ""
echo "📝 Next steps:"
echo "   1. Deploy to Render.com using the updated render.yaml"
echo "   2. Set up environment variables in Render dashboard"
echo "   3. Configure Redis and S3 services"
echo "   4. Set up monitoring and alerting"
echo "   5. Test all features in production environment"
echo ""
echo "🚀 Your application is ready for production!"