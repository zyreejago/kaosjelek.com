<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingInfo extends Model
{
    protected $fillable = [
        'order_id',
        'recipient_name',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'shipping_method',
        'tracking_number',
        'shipping_cost'
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->city . ', ' . $this->province . ' ' . $this->postal_code;
    }
}
