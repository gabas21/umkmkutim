<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:db {--path= : Relative path under storage/app to write backups (default: backups)} {--compress : Create ZIP of storage/app and public/uploads after DB dump} {--no-files : Do not backup files even if --compress specified}';

    protected $description = 'Create a database backup (mysqldump/pg_dump/SQLite copy) and optional storage files zip. Writes files into storage/app/<path>/';

    public function handle()
    {
        $fs = new Filesystem();
        $relPath = $this->option('path') ?: 'backups';
        $storagePath = storage_path('app/' . trim($relPath, '/'));
        $fs->ensureDirectoryExists($storagePath);

        $connection = config('database.default');
        $now = date('Ymd_His');
        $dbFile = "db_backup_{$now}.sql";
        $outFile = $storagePath . DIRECTORY_SEPARATOR . $dbFile;

        $this->info("Database connection: {$connection}");

        if ($connection === 'sqlite') {
            $sqlitePath = database_path(config('database.connections.sqlite.database'));
            if (!file_exists($sqlitePath)) {
                $this->error("SQLite database file not found: {$sqlitePath}");
                return 1;
            }
            copy($sqlitePath, $outFile);
            $this->info("SQLite DB copied to: {$outFile}");
        } elseif ($connection === 'mysql') {
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Use MYSQL_PWD env var to avoid exposing password in command args
            putenv("MYSQL_PWD={$password}");

            $cmd = 'mysqldump --single-transaction --quick --lock-tables=false '
                . ' -h ' . escapeshellarg($host)
                . ' -P ' . escapeshellarg($port)
                . ' -u ' . escapeshellarg($username)
                . ' ' . escapeshellarg($database)
                . ' > ' . escapeshellarg($outFile);

            $this->info('Running mysqldump...');
            passthru($cmd, $exit);
            if ($exit !== 0) {
                $this->error("mysqldump failed with exit code {$exit}");
                return 1;
            }
            $this->info("MySQL dump written to: {$outFile}");
            // unset env
            putenv('MYSQL_PWD');
        } elseif ($connection === 'pgsql') {
            $host = config('database.connections.pgsql.host');
            $port = config('database.connections.pgsql.port');
            $database = config('database.connections.pgsql.database');
            $username = config('database.connections.pgsql.username');
            $password = config('database.connections.pgsql.password');

            putenv("PGPASSWORD={$password}");

            $cmd = 'pg_dump -h ' . escapeshellarg($host)
                . ' -p ' . escapeshellarg($port)
                . ' -U ' . escapeshellarg($username)
                . ' -F p -f ' . escapeshellarg($outFile) . ' ' . escapeshellarg($database);

            $this->info('Running pg_dump...');
            passthru($cmd, $exit);
            if ($exit !== 0) {
                $this->error("pg_dump failed with exit code {$exit}");
                return 1;
            }
            $this->info("Postgres dump written to: {$outFile}");
            putenv('PGPASSWORD');
        } else {
            $this->error("Unsupported DB connection: {$connection}");
            return 1;
        }

        $responses = [
            'db' => $outFile,
        ];

        if ($this->option('compress') && !$this->option('no-files')) {
            $zipName = "files_backup_{$now}.zip";
            $zipPath = $storagePath . DIRECTORY_SEPARATOR . $zipName;

            $this->info('Creating ZIP of storage/app and public/uploads (this may take a while)...');

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                $this->error('Unable to create ZIP file: ' . $zipPath);
            } else {
                // add storage/app
                $base1 = storage_path('app');
                $this->addFolderToZip($base1, $zip, strlen(dirname($base1)) + 1);
                // add public/uploads if exists
                $base2 = public_path('uploads');
                if (is_dir($base2)) {
                    $this->addFolderToZip($base2, $zip, strlen(dirname($base2)) + 1);
                }
                $zip->close();
                $this->info("Files ZIP created: {$zipPath}");
                $responses['files'] = $zipPath;
            }
        }

        $this->info("Backup completed. Files written to: {$storagePath}");
        foreach ($responses as $k => $v) {
            $this->line("- {$k}: {$v}");
        }

        return 0;
    }

    private function addFolderToZip(string $folder, \ZipArchive $zip, int $stripLen)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
        foreach ($files as $file) {
            if (!$file->isFile()) continue;
            $filePath = $file->getRealPath();
            // relative path inside zip
            $localName = substr($filePath, $stripLen);
            $zip->addFile($filePath, $localName);
        }
    }
}
