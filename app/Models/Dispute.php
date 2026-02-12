<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'description',
        'status',
        'admin_response',
        'reviewed_by',
        'reviewed_at',
        'resolved_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}
