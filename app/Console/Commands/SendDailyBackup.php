<?php

namespace App\Console\Commands;

use App\Mail\DailyBackupMail;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SendDailyBackup extends Command
{
    protected $signature = 'backup:daily-send';

    protected $description = 'Take a DB backup and email it to configured recipients';

    public function handle(): int
    {
        $setting = Setting::first();
        $recipients = $setting?->backup_emails ?? [];

        if (empty($recipients)) {
            $this->info('No backup emails configured. Skip sending.');
            return self::SUCCESS;
        }

        $disk = Storage::disk('local');
        $fileName = 'db-backup-' . now()->format('Y-m-d_H-i-s') . '-' . Str::random(5) . '.sql.gz';
        $path = 'backups/' . $fileName;

        // Ensure directory exists
        $disk->makeDirectory('backups');

        // Run mysqldump and gzip it
        $db = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s | gzip > %s',
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['host']),
            escapeshellarg($db['port']),
            escapeshellarg($db['database']),
            escapeshellarg(storage_path('app/' . $path))
        );

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result !== 0) {
            $this->error('Backup failed. Check mysqldump availability/credentials.');
            return self::FAILURE;
        }

        // Email the backup
        foreach ($recipients as $email) {
            Mail::to($email)->queue(new DailyBackupMail($fileName, storage_path('app/' . $path)));
        }

        $this->info('Backup created and queued for sending.');
        return self::SUCCESS;
    }
}

