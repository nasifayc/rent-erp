<?php

namespace App\Services;

use App\Models\BranchUtility;
use App\Models\Notification;
use App\Models\OfficeRentAgreement;
use App\Models\UtilityPayment;
use App\Models\VehicleInspection;
use App\Models\VehicleLicense;
use App\Models\Vehicle;
use App\Models\VehicleServiceRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ErpNotificationEngine
{
    public function generateDailyAlerts(): int
    {
        $today = Carbon::today();
        $count = 0;

        $count += $this->generateAgreementExpiryAlerts($today);
        $count += $this->generateVehicleLicenseAlerts($today);
        $count += $this->generateVehicleInspectionAlerts($today);
        $count += $this->generateUtilityDueAlerts($today);
        $count += $this->generateServiceDueAlerts($today);
        $count += $this->generateServiceRequestApprovalAlerts();

        return $count;
    }

    private function generateAgreementExpiryAlerts(Carbon $today): int
    {
        $days = [90, 60, 30];
        $created = 0;

        foreach ($days as $day) {
            $targetDate = $today->copy()->addDays($day);

            $agreements = OfficeRentAgreement::query()
                ->whereDate('end_date', $targetDate)
                ->where('status', 'active')
                ->get();

            foreach ($agreements as $agreement) {
                $created += $this->createOnce(
                    type: 'agreement_expiry',
                    title: "Agreement {$agreement->agreement_id} expires in {$day} days",
                    message: "Office agreement for branch {$agreement->branch?->name} expires on {$agreement->end_date?->format('Y-m-d')}",
                    notifiableType: OfficeRentAgreement::class,
                    notifiableId: $agreement->id,
                    sentAt: $today,
                );
            }
        }

        return $created;
    }

    private function generateVehicleLicenseAlerts(Carbon $today): int
    {
        return $this->generateDateThresholdAlerts(
            records: VehicleLicense::query()->where('status', 'valid')->get(),
            expiryColumn: 'expiry_date',
            alertType: 'bolo_renewal',
            titlePrefix: 'Vehicle bolo/license expires',
            messagePrefix: 'Vehicle',
            notifiableType: VehicleLicense::class,
            today: $today,
        );
    }

    private function generateVehicleInspectionAlerts(Carbon $today): int
    {
        return $this->generateDateThresholdAlerts(
            records: VehicleInspection::query()->where('status', 'valid')->get(),
            expiryColumn: 'expiry_date',
            alertType: 'inspection_expiry',
            titlePrefix: 'Vehicle inspection expires',
            messagePrefix: 'Vehicle',
            notifiableType: VehicleInspection::class,
            today: $today,
        );
    }

    private function generateUtilityDueAlerts(Carbon $today): int
    {
        $created = 0;

        $payments = UtilityPayment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<=', $today->copy()->addDays(7))
            ->get();

        foreach ($payments as $payment) {
            $utility = $payment->utility;

            $created += $this->createOnce(
                type: 'utility_due',
                title: "Utility bill due: {$utility?->utility_type}",
                message: "{$utility?->provider} account {$utility?->account_number} due on {$payment->due_date?->format('Y-m-d')}",
                notifiableType: UtilityPayment::class,
                notifiableId: $payment->id,
                sentAt: $today,
            );
        }

        return $created;
    }

    private function generateServiceDueAlerts(Carbon $today): int
    {
        $created = 0;

        $vehicles = Vehicle::query()
            ->where('status', Vehicle::STATUS_ACTIVE)
            ->get();

        foreach ($vehicles as $vehicle) {
            $kmRemaining = $vehicle->kmUntilServiceDue();
            $daysRemaining = $vehicle->daysUntilInspectionDue();

            $isDueByMileage = $kmRemaining <= 0;
            $isDueByTime = $daysRemaining !== null && $daysRemaining <= 7;

            if (! $isDueByMileage && ! $isDueByTime) {
                continue;
            }

            $reason = $isDueByMileage
                ? "mileage threshold reached ({$vehicle->current_mileage} km)"
                : "time-based schedule due in {$daysRemaining} days";

            $created += $this->createOnce(
                type: 'service_due',
                title: 'Vehicle service is due',
                message: "Vehicle {$vehicle->plate_number} requires scheduled maintenance: {$reason}",
                notifiableType: Vehicle::class,
                notifiableId: $vehicle->id,
                sentAt: $today,
            );
        }

        return $created;
    }

    private function generateServiceRequestApprovalAlerts(): int
    {
        $created = 0;

        $requests = VehicleServiceRequest::query()
            ->where('status', 'approved')
            ->whereDate('approved_at', Carbon::today())
            ->get();

        foreach ($requests as $request) {
            $created += $this->createOnce(
                type: 'maintenance_request_approved',
                title: 'Maintenance request approved',
                message: "Service request #{$request->id} for vehicle {$request->vehicle?->plate_number} has been approved",
                notifiableType: VehicleServiceRequest::class,
                notifiableId: $request->id,
                sentAt: Carbon::today(),
            );
        }

        return $created;
    }

    private function generateDateThresholdAlerts(
        Collection $records,
        string $expiryColumn,
        string $alertType,
        string $titlePrefix,
        string $messagePrefix,
        string $notifiableType,
        Carbon $today,
    ): int {
        $days = [60, 30, 7];
        $created = 0;

        foreach ($records as $record) {
            $expiryDate = $record->{$expiryColumn};

            if (! $expiryDate) {
                continue;
            }

            $remaining = $today->diffInDays(Carbon::parse($expiryDate), false);

            if (! in_array($remaining, $days, true)) {
                continue;
            }

            $plateNumber = $record->vehicle?->plate_number ?? 'N/A';

            $created += $this->createOnce(
                type: $alertType,
                title: "{$titlePrefix} in {$remaining} days",
                message: "{$messagePrefix} {$plateNumber} expires on {$expiryDate}",
                notifiableType: $notifiableType,
                notifiableId: $record->id,
                sentAt: $today,
            );
        }

        return $created;
    }

    private function createOnce(
        string $type,
        string $title,
        string $message,
        string $notifiableType,
        int $notifiableId,
        Carbon $sentAt,
    ): int {
        $alreadyExists = Notification::query()
            ->where('type', $type)
            ->where('notifiable_type', $notifiableType)
            ->where('notifiable_id', $notifiableId)
            ->whereDate('sent_at', $sentAt)
            ->exists();

        if ($alreadyExists) {
            return 0;
        }

        Notification::query()->create([
            'type' => $type,
            'channel' => 'dashboard',
            'title' => $title,
            'message' => $message,
            'priority' => 'high',
            'status' => 'unread',
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'sent_at' => $sentAt,
        ]);

        return 1;
    }
}
