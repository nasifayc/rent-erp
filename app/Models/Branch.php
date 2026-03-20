<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'proposed_office',
        'landlord_name',
        'landlord_phone',
        'landlord_email',
        'estimated_rent',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'estimated_rent' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function officeRentAgreements(): HasMany
    {
        return $this->hasMany(OfficeRentAgreement::class);
    }

    public function utilities(): HasMany
    {
        return $this->hasMany(BranchUtility::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
