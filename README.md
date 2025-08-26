# Border Buyers - Production Ready

A secure and optimized Laravel application for connecting buyers and sellers with verified agents, now production-ready for deployment on Render.com.

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Redis (for production caching and sessions)
- Node.js (for asset compilation)

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd border-buyers
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci --production=false
   npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run deployment optimizations**
   ```bash
   chmod +x deployment-optimizations.sh
   ./deployment-optimizations.sh
   ```

5. **Deploy to Render.com**
   ```bash
   git push origin main
   ```

## 🌟 Features

### Core Features
- **Marketplace**: Browse and create listings for cross-border transactions
- **Agent Verification**: Connect with verified agents for secure transactions
- **User Management**: Role-based access control (admin, agent, buyer, seller)
- **Transaction Monitoring**: Real-time transaction tracking and security
- **AI Advisor**: Smart risk assessment and recommendations

### Production Optimizations
- **Advanced Caching**: Redis-based caching with intelligent TTL management
- **Database Optimization**: Eager loading, query optimization, and indexing
- **Security Hardening**: CSRF protection, secure headers, input validation
- **Error Handling**: Comprehensive error logging and user-friendly error pages
- **Performance**: Lazy loading optimizations and efficient data handling
- **Queue System**: Redis-based queue processing for background jobs

## 🏗️ Architecture

### Technology Stack
- **Backend**: Laravel 9 with PHP 8.0+
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL with Redis caching
- **File Storage**: AWS S3 integration
- **Email**: SendGrid integration
- **Deployment**: Render.com with auto-scaling

### Key Components
- **Authentication**: Laravel Sanctum for API authentication
- **Authorization**: Role-based policies and middleware
- **Caching**: Multi-layer caching strategy with Redis
- **Logging**: Structured logging with multiple channels
- **Monitoring**: Health checks and performance monitoring

## 📦 Production Deployment

### Render.com Configuration

The application comes with a pre-configured `render.yaml` file that includes:

- **Auto-scaling**: Automatic scaling based on CPU usage
- **Health Checks**: Comprehensive health monitoring
- **Environment Variables**: Production-ready configuration
- **Database**: PostgreSQL with automatic backups
- **Redis**: High-performance caching and session storage
- **File Storage**: AWS S3 integration for uploaded files

### Environment Variables

Configure these variables in your Render dashboard:

```bash
# Application
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-generated-key
APP_URL=https://your-app.onrender.com

# Database (automatically set by Render)
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=border_buyers
DB_USERNAME=border_buyer_user
DB_PASSWORD=your-db-password

# Redis (automatically set by Render)
REDIS_URL=your-redis-url
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# AWS S3
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=border-buyers-storage

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Border Buyers"

# Security
SANCTUM_STATEFUL_DOMAINS=your-app.onrender.com
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

## 🔧 Development

### Local Development Setup

1. **Install development dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Configure local environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Set up database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Run development server**
   ```bash
   php artisan serve
   npm run dev
   ```

### Testing

```bash
# Run PHPUnit tests
php artisan test

# Run feature tests
php artisan test --testsuite=Feature

# Run unit tests
php artisan test --testsuite=Unit
```

### Code Quality

```bash
# Run code style checks
./vendor/bin/phpcs --standard=PSR12 app/

# Run static analysis
./vendor/bin/phpstan analyse app/

# Run security analysis
./vendor/bin/security-checker security:check
```

## 📊 Monitoring & Maintenance

### Health Checks

The application includes a comprehensive health check endpoint at `/health` that monitors:

- Database connectivity
- Cache system status
- Application environment
- System resources
- Service availability

### Logging

Production logging includes multiple channels:

- **Application Logs**: Daily rotating logs for general application events
- **Security Logs**: Authentication and authorization events
- **Performance Logs**: Query performance and response times
- **API Logs**: API request/response logging
- **Audit Logs**: User activity and system changes

### Performance Monitoring

Key performance metrics are tracked:

- Cache hit rates
- Database query performance
- Response times
- Memory usage
- Queue processing times

## 🔒 Security Features

### Application Security
- **Authentication**: Multi-factor authentication ready
- **Authorization**: Role-based access control
- **Input Validation**: Comprehensive input sanitization
- **CSRF Protection**: Cross-site request forgery prevention
- **XSS Protection**: Cross-site scripting prevention
- **SQL Injection**: Parameterized queries and ORM protection

### Infrastructure Security
- **HTTPS**: Forced HTTPS redirection in production
- **Headers**: Security headers (X-Frame-Options, CSP, etc.)
- **Cookies**: Secure and HTTP-only cookies
- **Sessions**: Secure session management with Redis
- **File Upload**: Secure file handling and validation

## 🚀 Scaling

### Horizontal Scaling
- **Load Balancing**: Render.com provides automatic load balancing
- **Database**: Read replicas for high read volumes
- **Cache**: Redis cluster for distributed caching
- **File Storage**: CDN integration for static assets

### Vertical Scaling
- **Auto-scaling**: CPU-based auto-scaling configuration
- **Memory**: Optimized memory usage with caching
- **Storage**: Scalable S3 storage for uploads

## 📈 Performance Optimizations

### Database Optimizations
- **Eager Loading**: N+1 query prevention
- **Query Caching**: Intelligent query result caching
- **Indexing**: Optimized database indexes
- **Connection Pooling**: Efficient database connections

### Application Optimizations
- **Route Caching**: Cached routes for faster routing
- **View Caching**: Compiled Blade templates
- **Config Caching**: Cached configuration files
- **Asset Optimization**: Minified and compressed assets

### Caching Strategy
- **Multi-level Caching**: Application, database, and object caching
- **Intelligent TTL**: Time-to-live based on data type
- **Cache Invalidation**: Smart cache busting on model changes
- **Fallback Mechanisms**: Graceful degradation on cache failures

## 🛠️ Troubleshooting

### Common Issues

**500 Errors**
```bash
# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check logs
tail -f storage/logs/laravel.log
```

**Database Connection Issues**
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check database configuration
php artisan env
```

**Cache Issues**
```bash
# Clear Redis cache
redis-cli FLUSHALL

# Check Redis status
redis-cli PING
```

**Permission Issues**
```bash
# Fix file permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log
```

### Performance Debugging

```bash
# Check slow queries
grep "Slow query detected" storage/logs/laravel.log

# Monitor cache performance
redis-cli INFO stats

# Check application performance
php artisan about
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Use feature flags for experimental features
- Follow security best practices

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:

- **Documentation**: Check the `/docs` directory
- **Issues**: Create an issue in the repository
- **Emergency**: Contact the development team directly

## 🎯 Roadmap

### Upcoming Features
- [ ] Real-time notifications with WebSockets
- [ ] Advanced analytics dashboard
- [ ] Mobile API optimization
- [ ] Multi-language support
- [ ] Advanced fraud detection
- [ ] Integration with payment gateways

### Performance Improvements
- [ ] Database read replicas
- [ ] Advanced caching strategies
- [ ] CDN integration for static assets
- [ ] Queue worker optimization

---

**Built with ❤️ for secure cross-border transactions**
