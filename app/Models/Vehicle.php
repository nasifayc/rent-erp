<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'branch_id',
        'plate_number',
        'registration_number',
        'make',
        'model',
        'color',
        'manufacture_year',
        'current_mileage',
        'last_service_date',
        'service_interval_km',
        'inspection_interval_days',
        'major_service_interval_days',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'manufacture_year' => 'integer',
            'current_mileage' => 'integer',
            'last_service_date' => 'date',
            'service_interval_km' => 'integer',
            'inspection_interval_days' => 'integer',
            'major_service_interval_days' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(VehicleServiceRequest::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(VehicleMaintenanceRecord::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(VehicleLicense::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class);
    }

    public function kmUntilServiceDue(): int
    {
        $latestRecord = $this->maintenanceRecords()->latest('service_date')->first();

        if (! $latestRecord || ! $latestRecord->next_due_mileage) {
            return max(0, $this->service_interval_km - $this->current_mileage);
        }

        return (int) ($latestRecord->next_due_mileage - $this->current_mileage);
    }

    public function daysUntilInspectionDue(): ?int
    {
        if (! $this->last_service_date) {
            return null;
        }

        $dueDate = Carbon::parse($this->last_service_date)->addDays($this->inspection_interval_days);

        return (int) Carbon::today()->diffInDays($dueDate, false);
    }
}
