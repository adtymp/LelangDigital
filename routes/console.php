<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('proyek:update-status-proyek')->everyMinute();

Schedule::command('level:reset-level-user')->everyMinute();

Schedule::call(function () {
    $files = Storage::disk('public')->files('temp_pdf');
    foreach ($files as $file) {
        // Skip file .gitignore
        if (basename($file) === '.gitignore') continue;
        
        $lastModified = Storage::disk('public')->lastModified($file);
        // 86400 detik = 24 jam
        if (time() - $lastModified > 86400) {
            Storage::disk('public')->delete($file);
        }
    }
})->daily();

Schedule::command('cache:hapus-drive-cache')->daily();
