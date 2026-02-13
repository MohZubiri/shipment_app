<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyBackupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $fileName;
    public string $downloadPath;

    public function __construct(string $fileName, string $downloadPath)
    {
        $this->fileName = $fileName;
        $this->downloadPath = $downloadPath;
    }

    public function build(): self
    {
        return $this->subject('النسخة الاحتياطية اليومية لقاعدة البيانات')
            ->markdown('emails.daily-backup', [
                'fileName' => $this->fileName,
            ])
            ->attach($this->downloadPath, [
                'as' => $this->fileName,
                'mime' => 'application/gzip',
            ]);
    }
}

