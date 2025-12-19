<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'plan',
        'amount',
        'status',
        'payment_type',
        'snap_token',
        'raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}
