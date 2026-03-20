<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchUtility extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'utility_type',
        'provider',
        'account_number',
        'payment_cycle',
        'next_due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'next_due_date' => 'date',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(UtilityPayment::class);
    }
}
