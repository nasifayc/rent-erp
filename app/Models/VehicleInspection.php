<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleInspection extends Model
{
    use HasFactory;

    public const STATUS_VALID = 'valid';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_RENEWED = 'renewed';

    protected $fillable = [
        'vehicle_id',
        'inspection_date',
        'expiry_date',
        'status',
        'certificate_file',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
