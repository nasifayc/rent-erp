<?php

use App\Services\ErpNotificationEngine;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('erp:notifications-generate', function (ErpNotificationEngine $engine) {
    $created = $engine->generateDailyAlerts();

    $this->info("ERP notifications generated: {$created}");
})->purpose('Generate daily ERP alerts for agreements, vehicles, and utilities');

Schedule::command('erp:notifications-generate')->dailyAt('08:00');
