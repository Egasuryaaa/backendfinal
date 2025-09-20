<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic order cancellation every 10 minutes
Schedule::command('orders:cancel-expired')
        ->everyTenMinutes()
        ->withoutOverlapping()
        ->description('Cancel orders that have passed their payment deadline');
