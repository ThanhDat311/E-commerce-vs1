<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin = 1;
    case Staff = 2;
    case Customer = 3;
    case Vendor = 4;

    /**
     * Resolve a role slug to its integer ID.
     *
     * @param  string  $slug  e.g. 'admin', 'staff', 'vendor', 'customer'
     */
    public static function fromSlug(string $slug): self
    {
        return match ($slug) {
            'admin' => self::Admin,
            'staff' => self::Staff,
            'customer' => self::Customer,
            'vendor' => self::Vendor,
            default => throw new \ValueError("Unknown role slug: {$slug}"),
        };
    }
}
