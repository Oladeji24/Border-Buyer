<?php

/**
 * Border Buyers Production Cleanup Script
 * 
 * This script removes development files and optimizes the application
 * for production deployment.
 * 
 * Usage: php production-cleanup.php
 */

echo "🧹 Starting Border Buyers Production Cleanup\n";

// Files and directories to remove
$removeItems = [
    // Development files
    '.github',
    '.styleci.yml',
    'phpunit.xml',
    'webpack.mix.js',
    'postcss.config.js',
    
    // Documentation (keep only deployment guide)
    'README.md',
    'TODO.md',
    'OPTIMIZATION_SUMMARY.md',
    
    // Development dependencies (node_modules is handled by npm)
    'package-lock.json',
    
    // Backup directories (keep only latest)
    'backups',
    
    // Log files (keep structure but clear contents)
    'storage/logs/laravel.log',
    
    // Cache directories (will be rebuilt)
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
];

// Directories to keep but clear contents
$clearContents = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
];

// Files to keep but minimize
$keepButMinimize = [
    '.env.example' => '.env.example',
    'DEPLOYMENT_GUIDE.md' => 'DEPLOYMENT_GUIDE.md',
    'UNIT_9_VERIFICATION.md' => 'UNIT_9_VERIFICATION.md',
];

echo "📋 Cleanup plan:\n";
echo "• Remove development files and directories\n";
echo "• Clear cache and session data\n";
echo "• Keep essential documentation\n";
echo "• Optimize for production\n\n";

// Remove files and directories
foreach ($removeItems as $item) {
    if (file_exists($item) || is_dir($item)) {
        if (is_dir($item)) {
            echo "🗑️  Removing directory: $item\n";
            deleteDirectory($item);
        } else {
            echo "🗑️  Removing file: $item\n";
            unlink($item);
        }
    }
}

// Clear directory contents but keep structure
foreach ($clearContents as $dir) {
    if (is_dir($dir)) {
        echo "🧽 Clearing contents: $dir\n";
        clearDirectory($dir);
    }
}

// Run Laravel optimization commands
echo "⚡ Running Laravel optimization\n";
runCommand('php artisan optimize:clear');
runCommand('php artisan config:cache');
runCommand('php artisan route:cache');
runCommand('php artisan view:cache');
runCommand('php artisan event:cache');

// Remove dev dependencies from composer
echo "📦 Removing dev dependencies\n";
runCommand('composer install --no-dev --optimize-autoloader');

// Final optimization
echo "🎯 Final optimization\n";
runCommand('php artisan optimize');

echo "✅ Production cleanup completed!\n";
echo "📊 Application size: " . formatBytes(getDirectorySize('.')) . "\n";
echo "🚀 Ready for deployment!\n";

/**
 * Delete directory recursively
 */
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}

/**
 * Clear directory contents but keep directory
 */
function clearDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
}

/**
 * Run shell command
 */
function runCommand($command) {
    echo "   ➡️  $command\n";
    system($command, $returnCode);
    if ($returnCode !== 0) {
        echo "   ⚠️  Command failed: $command\n";
    }
    return $returnCode === 0;
}

/**
 * Get directory size in bytes
 */
function getDirectorySize($path) {
    $size = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
        $size += $file->getSize();
    }
    return $size;
}

/**
 * Format bytes to human readable format
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
