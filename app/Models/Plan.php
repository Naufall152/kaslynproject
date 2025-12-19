<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'key','name','price','duration_days','is_active','features'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
    ];
}
