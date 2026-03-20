<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'requested_by',
        'service_type',
        'problem_description',
        'urgency_level',
        'status',
        'service_provider',
        'approved_by',
        'approved_at',
        'service_date',
        'service_report_file',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'date',
            'service_date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
