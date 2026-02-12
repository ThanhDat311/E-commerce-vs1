<?php

namespace App\Models;

use App\Models\Scopes\VendorOrderScope;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Auditable, HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorOrderScope);
    }

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'note',
        'order_status',
        'payment_status',
        'payment_method',
        'total',
        'shipping_carrier',
        'tracking_number',
        'admin_note',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'desc');
    }

    public function getStatusAttribute()
    {
        return $this->order_status;
    }

    public function getTotalPriceAttribute()
    {
        return $this->total;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function aiFeature()
    {
        return $this->hasOne(AiFeatureStore::class, 'order_id');
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function activeDispute()
    {
        return $this->hasOne(Dispute::class)->whereIn('status', ['pending', 'under_review']);
    }
}
