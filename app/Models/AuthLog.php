<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'device_fingerprint',
        'user_agent',
        'geo_location',
        'risk_score',
        'risk_level',
        'auth_decision',
        'is_successful',
        'reasons',
    ];

    protected $casts = [
        'geo_location' => 'array',
        'reasons' => 'array',
        'is_successful' => 'boolean',
        'risk_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiFeature()
    {
        return $this->hasOne(AiFeatureStore::class);
    }
}
