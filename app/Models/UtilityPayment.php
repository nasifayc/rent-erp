<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_utility_id',
        'billing_month',
        'due_date',
        'amount_due',
        'amount_paid',
        'paid_at',
        'status',
        'recorded_by',
        'receipt_file',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'date',
            'due_date' => 'date',
            'paid_at' => 'date',
            'amount_due' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function utility(): BelongsTo
    {
        return $this->belongsTo(BranchUtility::class, 'branch_utility_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
