<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Maintenance notifications schedule
Schedule::command('maintenance:notify', ['--all'])
    ->dailyAt('08:00')
    ->withoutOverlapping();

// Check overdue tasks every 2 hours during business hours
Schedule::command('maintenance:notify', ['--overdue'])
    ->everyTwoHours()
    ->between('08:00', '18:00')
    ->withoutOverlapping();

// Low stock check daily at 9 AM
Schedule::command('maintenance:notify', ['--lowstock'])
    ->dailyAt('09:00')
    ->withoutOverlapping();
