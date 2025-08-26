#!/bin/bash

# Border Buyers Deployment Script
# Usage: ./deploy.sh [production|staging]

set -e  # Exit on error

ENVIRONMENT=${1:-production}
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="backups/$TIMESTAMP"

echo "🚀 Starting Border Buyers deployment to $ENVIRONMENT"
echo "📅 Deployment timestamp: $TIMESTAMP"

# Create backup directory
mkdir -p $BACKUP_DIR

# Function to run commands with error handling
run_command() {
    echo "➡️  Running: $1"
    if ! eval "$1"; then
        echo "❌ Command failed: $1"
        exit 1
    fi
}

# Backup current state
echo "📦 Creating backup..."
run_command "php artisan backup:run --destination=local --destinationPath=$BACKUP_DIR/backup.zip"

# Maintenance mode
run_command "php artisan down --message='Deployment in progress. Back soon!' --retry=60"

# Git operations
if [ "$ENVIRONMENT" = "production" ]; then
    run_command "git pull origin main"
fi

# Install dependencies
run_command "composer install --no-dev --optimize-autoloader"
run_command "npm install --production"

# Build assets
run_command "npm run build"

# Database operations
run_command "php artisan migrate --force"

# Cache optimization
run_command "php artisan optimize:clear"
run_command "php artisan config:cache"
run_command "php artisan route:cache"
run_command "php artisan view:cache"
run_command "php artisan event:cache"

# Storage link
run_command "php artisan storage:link"

# Set permissions
run_command "chmod 755 storage bootstrap/cache"
run_command "chmod 644 .env"

# Clear and rebuild cache
run_command "php artisan optimize"

# Restart queues
run_command "php artisan queue:restart"

# Bring application back online
run_command "php artisan up"

echo "✅ Deployment completed successfully!"
echo "📊 Health check: curl -s https://your-domain.com/health | jq"
echo "🕒 Deployment time: $(date)"
echo "📁 Backup saved to: $BACKUP_DIR/backup.zip"

# Health check
echo "🔍 Running health check..."
curl -s http://localhost/health > /dev/null && echo "✅ Health check passed!" || echo "⚠️  Health check may have issues"

echo "🎉 Border Buyers is now live on $ENVIRONMENT!"
