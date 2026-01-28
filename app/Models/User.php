<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;
use App\Traits\Auditable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',      // Dùng role_id để phân quyền
        'phone_number', // Giữ cái này (đã có sẵn trong DB)
        'address',      // [NEW] Thêm trường này
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function assignedRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function authLogs()
    {
        return $this->hasMany(AuthLog::class);
    }

    public function behaviorProfile()
    {
        return $this->hasOne(UserBehaviorProfile::class);
    }

    public function productRatings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role
            ->permissions
            ->contains('slug', $permissionSlug);
    }

    public function isAdmin()
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role_id === 1) {
            return true;
        }

        return false;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function isVendor()
    {
        return $this->role_id === 4;
    }

    public function hasRole(string $roleName): bool
    {
        $roleIds = [
            'admin' => 1,
            'staff' => 2,
            'customer' => 3,
            'vendor' => 4,
        ];

        return isset($roleIds[$roleName]) && $this->role_id === $roleIds[$roleName];
    }

    public function getRoleNameAttribute(): string
    {
        $roles = [
            1 => 'admin',
            2 => 'staff',
            3 => 'customer',
            4 => 'vendor',
        ];

        return $roles[$this->role_id] ?? 'unknown';
    }
}
