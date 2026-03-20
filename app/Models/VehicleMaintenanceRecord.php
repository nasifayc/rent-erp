<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'vehicle_service_request_id',
        'mileage_at_service',
        'service_date',
        'service_type',
        'provider',
        'cost',
        'notes',
        'next_due_date',
        'next_due_mileage',
    ];

    protected function casts(): array
    {
        return [
            'mileage_at_service' => 'integer',
            'service_date' => 'date',
            'cost' => 'decimal:2',
            'next_due_date' => 'date',
            'next_due_mileage' => 'integer',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(VehicleServiceRequest::class, 'vehicle_service_request_id');
    }
}
