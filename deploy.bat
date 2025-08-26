@echo off
echo 🚀 Starting Border Buyers Deployment
echo.

REM Set environment (default to production)
set ENVIRONMENT=%1
if "%ENVIRONMENT%"=="" set ENVIRONMENT=production

REM Get timestamp
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /format:list') do set datetime=%%I
set TIMESTAMP=%datetime:~0,4%%datetime:~4,2%%datetime:~6,2%_%datetime:~8,2%%datetime:~10,2%%datetime:~12,2%
set BACKUP_DIR=backups\%TIMESTAMP%

echo 📅 Deployment timestamp: %TIMESTAMP%
echo 📦 Environment: %ENVIRONMENT%

REM Create backup directory
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Function to run commands with error handling
:run_command
echo ➡️  Running: %*
%*
if %errorlevel% neq 0 (
    echo ❌ Command failed: %*
    exit /b 1
)
goto :eof

REM Backup current state
echo 📦 Creating backup...
call :run_command php artisan backup:run --destination=local --destinationPath="%BACKUP_DIR%/backup.zip"

REM Maintenance mode
call :run_command php artisan down --message="Deployment in progress. Back soon!" --retry=60

REM Git operations (if production)
if "%ENVIRONMENT%"=="production" (
    call :run_command git pull origin main
)

REM Install dependencies
call :run_command composer install --no-dev --optimize-autoloader
call :run_command npm install --production

REM Build assets
call :run_command npm run build

REM Database operations
call :run_command php artisan migrate --force

REM Cache optimization
call :run_command php artisan optimize:clear
call :run_command php artisan config:cache
call :run_command php artisan route:cache
call :run_command php artisan view:cache
call :run_command php artisan event:cache

REM Storage link
call :run_command php artisan storage:link

REM Set permissions (Windows equivalent)
icacls storage /grant Everyone:(OI)(CI)F
icacls bootstrap/cache /grant Everyone:(OI)(CI)F

REM Clear and rebuild cache
call :run_command php artisan optimize

REM Restart queues
call :run_command php artisan queue:restart

REM Bring application back online
call :run_command php artisan up

echo ✅ Deployment completed successfully!
echo 📊 Health check: curl -s http://localhost/health
echo 🕒 Deployment time: %date% %time%
echo 📁 Backup saved to: %BACKUP_DIR%/backup.zip

REM Simple health check
echo 🔍 Running health check...
curl -s http://localhost/health > nul
if %errorlevel% equ 0 (
    echo ✅ Health check passed!
) else (
    echo ⚠️  Health check may have issues
)

echo 🎉 Border Buyers is now live on %ENVIRONMENT%!
pause
