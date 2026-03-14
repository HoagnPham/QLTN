<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'contract_id',
        'invoice_no',
        'billing_month',
        'due_date',
        'rent',
        'electricity_previous',
        'electricity_current',
        'electricity_price',
        'water_previous',
        'water_current',
        'water_price',
        'service_fee',
        'electricity_usage',
        'water_usage',
        'total_amount',
        'status',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
