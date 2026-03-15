<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskList extends Model
{
    protected $fillable = [
        'type',       // 'ip' or 'user_id'
        'value',      // The IP address or User ID
        'action',     // 'block' or 'whitelist'
        'reason',     // Optional description
        'expires_at', // Optional expiration
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
