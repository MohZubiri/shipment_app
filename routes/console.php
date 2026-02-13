<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendDailyBackup;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('backup:test-send', function () {
    $this->call(SendDailyBackup::class);
})->purpose('Run the daily backup mail command once for testing');
