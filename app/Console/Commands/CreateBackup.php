<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create 
                            {--database : Backup database only}
                            {--files : Backup files only}
                            {--destination=local : Backup destination (local, s3)}
                            {--retain=7 : Number of backups to retain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create backup of database and files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting backup process...');

        $backupDatabase = $this->option('database') || !$this->option('files');
        $backupFiles = $this->option('files') || !$this->option('database');
        $destination = $this->option('destination');
        $retainBackups = (int) $this->option('retain');

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupPath = "backups/{$timestamp}";

        try {
            if ($backupDatabase) {
                $this->backupDatabase($backupPath, $destination);
            }

            if ($backupFiles) {
                $this->backupFiles($backupPath, $destination);
            }

            $this->cleanupOldBackups($destination, $retainBackups);

            $this->info('Backup completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error("Backup failed: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Backup database
     *
     * @param  string  $backupPath
     * @param  string  $destination
     * @return void
     */
    protected function backupDatabase($backupPath, $destination)
    {
        $this->info('Backing up database...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $filename = "database_{$database}_{$timestamp}.sql";
        $backupFile = storage_path("app/temp/{$filename}");

        // Create backup directory
        File::ensureDirectoryExists(storage_path('app/temp'));

        // Create database backup
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%d --single-transaction --routines --triggers %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            $port,
            escapeshellarg($database),
            escapeshellarg($backupFile)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('Database backup failed');
        }

        // Compress the backup file
        $compressedFile = $backupFile . '.gz';
        exec("gzip -c {$backupFile} > {$compressedFile}");

        // Store the backup
        $storageDisk = Storage::disk($destination);
        $storagePath = "{$backupPath}/database/{$filename}.gz";

        $storageDisk->put($storagePath, file_get_contents($compressedFile));

        // Clean up temporary files
        File::delete([$backupFile, $compressedFile]);

        $this->info("Database backup created: {$storagePath}");
    }

    /**
     * Backup files
     *
     * @param  string  $backupPath
     * @param  string  $destination
     * @return void
     */
    protected function backupFiles($backupPath, $destination)
    {
        $this->info('Backing up files...');

        $pathsToBackup = [
            'storage/app/public' => 'public',
            'resources/views' => 'views',
            'app' => 'app',
            'config' => 'config',
            'routes' => 'routes',
        ];

        $storageDisk = Storage::disk($destination);

        foreach ($pathsToBackup as $sourcePath => $backupType) {
            if (!File::exists(base_path($sourcePath))) {
                continue;
            }

            $this->line("Backing up {$sourcePath}...");

            $tempFile = storage_path("app/temp/files_{$backupType}_{$timestamp}.tar.gz");
            File::ensureDirectoryExists(storage_path('app/temp'));

            // Create compressed archive
            $command = sprintf(
                'tar -czf %s -C %s %s',
                escapeshellarg($tempFile),
                escapeshellarg(base_path()),
                escapeshellarg($sourcePath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->warn("Failed to backup {$sourcePath}");
                continue;
            }

            // Store the backup
            $filename = "files_{$backupType}_{$timestamp}.tar.gz";
            $storagePath = "{$backupPath}/files/{$filename}";

            $storageDisk->put($storagePath, file_get_contents($tempFile));

            // Clean up temporary file
            File::delete($tempFile);

            $this->info("Files backup created: {$storagePath}");
        }
    }

    /**
     * Clean up old backups
     *
     * @param  string  $destination
     * @param  int  $retainBackups
     * @return void
     */
    protected function cleanupOldBackups($destination, $retainBackups)
    {
        $this->info('Cleaning up old backups...');

        $storageDisk = Storage::disk($destination);
        $backups = $storageDisk->directories('backups');

        if (count($backups) <= $retainBackups) {
            return;
        }

        // Sort backups by name (which includes timestamp)
        sort($backups);

        // Remove oldest backups
        $backupsToRemove = array_slice($backups, 0, count($backups) - $retainBackups);

        foreach ($backupsToRemove as $backup) {
            $storageDisk->deleteDirectory("backups/{$backup}");
            $this->info("Removed old backup: {$backup}");
        }
    }

    /**
     * Get backup statistics
     *
     * @return array
     */
    public function getBackupStats()
    {
        $stats = [
            'total_backups' => 0,
            'total_size' => 0,
            'latest_backup' => null,
            'backups_by_type' => [
                'database' => 0,
                'files' => 0,
            ],
        ];

        try {
            $storageDisk = Storage::disk(config('backup.destination', 'local'));
            $backups = $storageDisk->directories('backups');

            $stats['total_backups'] = count($backups);

            if (!empty($backups)) {
                sort($backups);
                $stats['latest_backup'] = end($backups);

                // Calculate total size
                foreach ($backups as $backup) {
                    $backupPath = "backups/{$backup}";
                    
                    if ($storageDisk->exists("{$backupPath}/database")) {
                        $stats['backups_by_type']['database']++;
                    }
                    
                    if ($storageDisk->exists("{$backupPath}/files")) {
                        $stats['backups_by_type']['files']++;
                    }

                    $stats['total_size'] += $this->getDirectorySize($storageDisk, $backupPath);
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to get backup stats: {$e->getMessage()}");
        }

        return $stats;
    }

    /**
     * Get directory size
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter  $storageDisk
     * @param  string  $path
     * @return int
     */
    protected function getDirectorySize($storageDisk, $path)
    {
        $size = 0;
        $files = $storageDisk->allFiles($path);

        foreach ($files as $file) {
            $size += $storageDisk->size($file);
        }

        return $size;
    }

    /**
     * Restore backup
     *
     * @param  string  $backupTimestamp
     * @param  bool  $restoreDatabase
     * @param  bool  $restoreFiles
     * @return void
     */
    public function restoreBackup($backupTimestamp, $restoreDatabase = true, $restoreFiles = true)
    {
        $destination = config('backup.destination', 'local');
        $storageDisk = Storage::disk($destination);
        $backupPath = "backups/{$backupTimestamp}";

        if (!$storageDisk->exists($backupPath)) {
            throw new \Exception("Backup not found: {$backupTimestamp}");
        }

        $tempPath = storage_path("app/restore/{$backupTimestamp}");
        File::ensureDirectoryExists($tempPath);

        try {
            if ($restoreDatabase && $storageDisk->exists("{$backupPath}/database")) {
                $this->restoreDatabase($storageDisk, $backupPath, $tempPath);
            }

            if ($restoreFiles && $storageDisk->exists("{$backupPath}/files")) {
                $this->restoreFiles($storageDisk, $backupPath, $tempPath);
            }

            $this->info("Backup {$backupTimestamp} restored successfully!");

        } finally {
            // Clean up temporary files
            File::deleteDirectory($tempPath);
        }
    }

    /**
     * Restore database from backup
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter  $storageDisk
     * @param  string  $backupPath
     * @param  string  $tempPath
     * @return void
     */
    protected function restoreDatabase($storageDisk, $backupPath, $tempPath)
    {
        $this->info('Restoring database...');

        $databaseFiles = $storageDisk->files("{$backupPath}/database");
        
        if (empty($databaseFiles)) {
            $this->warn('No database backup files found');
            return;
        }

        foreach ($databaseFiles as $databaseFile) {
            $filename = basename($databaseFile, '.gz');
            $tempFile = "{$tempPath}/{$filename}";
            
            // Download and decompress
            $compressedContent = $storageDisk->get($databaseFile);
            file_put_contents($tempFile . '.gz', $compressedContent);
            exec("gunzip -c {$tempFile}.gz > {$tempFile}");

            // Restore database
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s --port=%d %s < %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                $port,
                escapeshellarg($database),
                escapeshellarg($tempFile)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception("Database restore failed for {$filename}");
            }

            File::delete([$tempFile, $tempFile . '.gz']);
            $this->info("Database restored from {$filename}");
        }
    }

    /**
     * Restore files from backup
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter  $storageDisk
     * @param  string  $backupPath
     * @param  string  $tempPath
     * @return void
     */
    protected function restoreFiles($storageDisk, $backupPath, $tempPath)
    {
        $this->info('Restoring files...');

        $fileBackups = $storageDisk->files("{$backupPath}/files");
        
        if (empty($fileBackups)) {
            $this->warn('No file backup files found');
            return;
        }

        foreach ($fileBackups as $fileBackup) {
            $filename = basename($fileBackup, '.tar.gz');
            $tempFile = "{$tempPath}/{$filename}.tar.gz";
            
            // Download backup file
            $content = $storageDisk->get($fileBackup);
            file_put_contents($tempFile, $content);

            // Extract files
            exec("tar -xzf {$tempFile} -C " . escapeshellarg(base_path()));

            File::delete($tempFile);
            $this->info("Files restored from {$filename}");
        }
    }
}