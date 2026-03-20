<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeRentAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'agreement_id',
        'landlord_name',
        'property_address',
        'monthly_rent',
        'payment_schedule',
        'start_date',
        'end_date',
        'status',
        'contract_file',
        'approved_at',
        'review_notes',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'monthly_rent' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'date',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(AgreementRenewal::class);
    }

    public function daysUntilExpiry(): int
    {
        return (int) Carbon::today()->diffInDays($this->end_date, false);
    }
}
