<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgreementRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_rent_agreement_id',
        'old_rent',
        'new_rent',
        'old_end_date',
        'new_start_date',
        'new_end_date',
        'decision',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'old_rent' => 'decimal:2',
            'new_rent' => 'decimal:2',
            'old_end_date' => 'date',
            'new_start_date' => 'date',
            'new_end_date' => 'date',
            'approved_at' => 'date',
        ];
    }

    public function officeRentAgreement(): BelongsTo
    {
        return $this->belongsTo(OfficeRentAgreement::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
