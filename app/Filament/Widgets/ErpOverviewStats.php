<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use App\Models\OfficeRentAgreement;
use App\Models\UtilityPayment;
use App\Models\VehicleInspection;
use App\Models\VehicleLicense;
use App\Models\VehicleServiceRequest;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ErpOverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $contractsExpiringSoon = OfficeRentAgreement::query()
            ->where('status', 'active')
            ->whereDate('end_date', '<=', $today->copy()->addDays(90))
            ->count();

        $pendingLegalApprovals = OfficeRentAgreement::query()
            ->where('status', 'pending_legal')
            ->count();

        $pendingServiceRequests = VehicleServiceRequest::query()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        $utilityBillsDue = UtilityPayment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<=', $today->copy()->addDays(14))
            ->count();

        $complianceDeadlines = VehicleLicense::query()
            ->whereDate('expiry_date', '<=', $today->copy()->addDays(60))
            ->count() + VehicleInspection::query()
            ->whereDate('expiry_date', '<=', $today->copy()->addDays(60))
            ->count();

        $unreadNotifications = Notification::query()
            ->where('status', 'unread')
            ->count();

        return [
            Stat::make('Contracts Expiring <= 90 Days', (string) $contractsExpiringSoon)
                ->description('Office rent contracts requiring action'),
            Stat::make('Pending Legal Approvals', (string) $pendingLegalApprovals)
                ->description('Agreements waiting legal review'),
            Stat::make('Service Requests Open', (string) $pendingServiceRequests)
                ->description('Vehicle maintenance workflow queue'),
            Stat::make('Utility Bills Due <= 14 Days', (string) $utilityBillsDue)
                ->description('Finance follow-up required'),
            Stat::make('Bolo / Inspection Deadlines', (string) $complianceDeadlines)
                ->description('Compliance items due <= 60 days'),
            Stat::make('Unread Notifications', (string) $unreadNotifications)
                ->description('Dashboard alerts not yet acknowledged'),
        ];
    }
}
