<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

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
}
