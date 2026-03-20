<?php

use App\Mail\ErpAlertMail;
use App\Models\Notification;
use App\Models\User;
use App\Services\ErpNotificationEngine;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('erp:notifications-generate', function (ErpNotificationEngine $engine) {
    $created = $engine->generateDailyAlerts();

    $this->info("ERP notifications generated: {$created}");
})->purpose('Generate daily ERP alerts for agreements, vehicles, and utilities');

Artisan::command('erp:notifications-dispatch-email', function () {
    $notifications = Notification::query()
        ->where('status', 'unread')
        ->where('channel', 'dashboard')
        ->whereDate('created_at', now()->toDateString())
        ->latest()
        ->limit(50)
        ->get();

    if ($notifications->isEmpty()) {
        $this->info('No notifications to dispatch by email.');

        return;
    }

    $recipients = User::query()
        ->where('is_admin', true)
        ->orWhereIn('role', [
            User::ROLE_ADMIN_OFFICER,
            User::ROLE_LEGAL_REVIEWER,
            User::ROLE_FLEET_MANAGER,
            User::ROLE_FINANCE_OFFICER,
        ])
        ->pluck('email')
        ->unique()
        ->values();

    foreach ($notifications as $notification) {
        foreach ($recipients as $email) {
            Mail::to($email)->send(new ErpAlertMail($notification));
        }
    }

    $this->info('ERP notification emails dispatched.');
})->purpose('Dispatch ERP notifications to email recipients');

Artisan::command('erp:notifications-dispatch-sms', function () {
    // Optional SMS delivery point for integration with providers like Twilio/AfricasTalking.
    Log::info('SMS dispatch placeholder executed for ERP notifications.');

    $this->info('SMS dispatch placeholder executed. Integrate provider credentials to enable SMS.');
})->purpose('Optional SMS dispatch placeholder for ERP notifications');

Schedule::command('erp:notifications-generate')->dailyAt('08:00');
Schedule::command('erp:notifications-dispatch-email')->dailyAt('08:05');
