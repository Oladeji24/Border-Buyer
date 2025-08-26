# Unit 10 - Deployment Guide for Border Buyers

## 🚀 Deployment Options

### Option 1: Render Deployment (Recommended)
Render provides modern cloud hosting with automatic deployments from GitHub.

### Option 2: cPanel/Shared Hosting
Traditional shared hosting deployment using cPanel.

---

## 📋 Pre-Deployment Checklist

### Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update environment variables for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password

# Cache configuration (Redis recommended for production)
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="Border Buyers"
```

---

## 🎯 Render Deployment Guide

### 1. Prepare Render Configuration
Create `render.yaml` in root directory:

```yaml
services:
  - type: web
    name: border-buyers
    env: php
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      php artisan optimize:clear
      php artisan optimize
    startCommand: |
      php artisan migrate --force
      php artisan storage:link
      php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: LOG_CHANNEL
        value: stderr
```

### 2. Environment Variables for Render
Set these in Render dashboard:
- `APP_KEY` - Generated with `php artisan key:generate`
- `DB_*` - Database connection details
- `REDIS_URL` - Redis connection string
- `MAIL_*` - Email service configuration

### 3. Build Settings
- **Runtime**: PHP 8.2+
- **Build Command**: `composer install --no-dev --optimize-autoloader`
- **Start Command**: See render.yaml above

---

## 🖥️ cPanel Deployment Guide

### 1. File Upload
```bash
# Compress project files (excluding development files)
tar -czf border-buyers.tar.gz \
  --exclude=node_modules \
  --exclude=storage/*/cache \
  --exclude=storage/*/logs \
  --exclude=storage/*/sessions \
  --exclude=storage/*/views \
  --exclude=.git \
  --exclude=.github \
  --exclude=tests \
  .
```

### 2. cPanel Steps
1. Upload the compressed file to your cPanel account
2. Extract files to your public_html or desired directory
3. Set proper permissions:
   ```bash
   chmod 755 storage bootstrap/cache
   chmod 644 .env
   ```

### 3. Database Setup
1. Create MySQL database via cPanel
2. Create database user and assign privileges
3. Import database schema if needed

---

## 🔧 Deployment Commands

### Essential Commands
```bash
# Install dependencies (production)
composer install --no-dev --optimize-autoloader
npm install --production

# Build assets
npm run build

# Environment setup
php artisan key:generate --force
php artisan storage:link
php artisan optimize:clear

# Database migration
php artisan migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Queue setup (if using queues)
php artisan queue:restart
```

### Post-Deployment Commands
```bash
# Clear and rebuild cache
php artisan optimize

# Seed initial data (optional)
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ReviewSeeder

# Health check
php artisan about
```

---

## 🛠️ Production Optimization

### Nginx Configuration (for VPS)
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
}
```

### Apache .htaccess (for shared hosting)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🔒 Security Configuration

### Environment Security
```bash
# Disable debug mode
APP_DEBUG=false

# Enable HTTPS
APP_URL=https://your-domain.com

# Secure session and cookie
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

# CSP headers (optional)
CSP_ENABLED=true
```

### File Permissions
```bash
# Secure file permissions
chmod 644 .env
chmod 755 storage bootstrap/cache
chmod 755 public/uploads
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;
```

---

## 📊 Monitoring & Maintenance

### Health Check Endpoint
Create `routes/health.php`:
```php
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'environment' => app()->environment(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
    ]);
});
```

### Log Rotation
Configure log rotation in `config/logging.php`:
```php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => 'debug',
    'days' => 14, // Keep logs for 14 days
],
```

### Backup Commands
```bash
# Database backup
php artisan backup:run

# File backup
tar -czf backup-$(date +%Y%m%d).tar.gz --exclude=node_modules --exclude=storage/logs .
```

---

## 🚨 Troubleshooting

### Common Issues
1. **500 Error**: Check .env configuration and file permissions
2. **White Screen**: Run `php artisan optimize:clear`
3. **Database Issues**: Verify credentials and run migrations
4. **Asset Issues**: Run `npm run build` and `php artisan storage:link`

### Debug Commands
```bash
# Check environment
php artisan env

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
```

---

## 📈 Performance Optimization for Production

### Cache Configuration
```php
// config/cache.php - Use Redis for production
'default' => env('CACHE_DRIVER', 'redis'),

// config/database.php - Redis configuration
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
],
```

### Queue Workers (for background processing)
```bash
# Start queue worker
php artisan queue:work --daemon

# Or use supervisor configuration
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/app/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/worker.log
```

This deployment guide provides comprehensive instructions for deploying Border Buyers to both modern cloud platforms (Render) and traditional shared hosting (cPanel).
