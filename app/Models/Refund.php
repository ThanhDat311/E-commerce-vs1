<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'dispute_id',
        'amount',
        'reason',
        'status',
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dispute()
    {
        return $this->belongsTo(Dispute::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
