# Vendor Portal - Quick Reference Guide

## 📊 Permission Matrix Quick View

```
┌─────────────────────────────┬──────┬───────┬────────┬──────────┐
│ Feature                     │Admin │ Staff │ Vendor │ Customer │
├─────────────────────────────┼──────┼───────┼────────┼──────────┤
│ Dashboard                   │  ✅  │  ✅   │   ✅   │    ❌    │
│ View All Products           │  ✅  │  ✅   │   ❌   │    ✅    │
│ View Own Products           │ N/A  │ N/A   │   ✅   │   N/A    │
│ Create Products             │  ✅  │  ✅   │   ✅   │    ❌    │
│ Edit All Products           │  ✅  │  ✅   │   ❌   │    ❌    │
│ Edit Own Products           │ N/A  │ N/A   │   ✅   │   N/A    │
│ Delete Products             │  ✅  │  ❌   │  ✅*  │    ❌    │
│ View All Orders             │  ✅  │  ✅   │   ❌   │    ❌    │
│ View Related Orders         │ N/A  │ N/A   │   ✅   │   N/A    │
│ Manage Users                │  ✅  │  ❌   │   ❌   │    ❌    │
│ View Reports                │  ✅  │  ❌   │   ❌   │    ❌    │
│ System Settings             │  ✅  │  ❌   │   ❌   │    ❌    │
└─────────────────────────────┴──────┴───────┴────────┴──────────┘
* = own products only
```

---

## 🗂️ View Directory Structure

```
resources/views/
├── layouts/
│   ├── admin.blade.php       ← Admin/Staff shared layout
│   └── vendor.blade.php      ← NEW: Vendor-only layout
├── admin/
│   ├── partials/
│   │   ├── sidebar.blade.php (with @role directives)
│   │   └── navbar.blade.php
│   └── [admin features]
└── vendor/                   ← NEW: Vendor Portal views
    ├── partials/
    │   ├── sidebar.blade.php (vendor-specific)
    │   └── navbar.blade.php
    ├── dashboard.blade.php
    ├── products/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    └── orders/
        ├── index.blade.php
        └── show.blade.php
```

---

## 🛡️ Security Layers

### 1️⃣ Route Middleware

```php
Route::prefix('vendor')
    ->middleware(['auth', 'role:vendor'])
    ->group(function () { ... });
```

### 2️⃣ Global Scopes

```php
// VendorScope automatically filters:
// WHERE vendor_id = current_user_id

Product::all(); // Only their products
Order::all();   // Only their orders
```

### 3️⃣ Policy Authorization

```php
public function update(User $user, Product $product): bool {
    return $product->vendor_id === $user->id;
}

// Usage:
$this->authorize('update', $product);
```

### 4️⃣ Controller Validation

```php
if (!$hasVendorProduct) {
    abort(403, 'Unauthorized');
}
```

---

## 🚀 Vendor Routes Map

```
GET    /vendor                     → Dashboard
GET    /vendor/products            → List products
POST   /vendor/products            → Create product
GET    /vendor/products/create     → Create form
GET    /vendor/products/{id}/edit  → Edit form
PUT    /vendor/products/{id}       → Update product
DELETE /vendor/products/{id}       → Delete product
GET    /vendor/orders              → List orders
GET    /vendor/orders/{id}         → View order
PUT    /vendor/orders/{id}/status  → Update status
```

---

## 🔑 Key Controllers

### VendorDashboardController

**Location**: `app/Http/Controllers/Vendor/DashboardController.php`

```php
public function index() {
    // Shows:
    // - Total products count
    // - Low stock count
    // - Orders containing their products
    // - Recent orders
}
```

### VendorProductController

**Location**: `app/Http/Controllers/Vendor/ProductController.php`

```php
Features:
✅ index()  - Lists vendor's products (auto-scoped)
✅ create() - Create form
✅ store()  - Auto-assigns vendor_id to current user
✅ edit()   - Uses authorize('update', $product)
✅ update() - Ensures vendor_id cannot be changed
✅ delete() - Own products only
```

### VendorOrderController

**Location**: `app/Http/Controllers/Vendor/OrderController.php`

```php
Features:
✅ index()         - Orders containing vendor's products
✅ show()          - View order details
✅ updateStatus()  - Change order status with audit trail
```

---

## 🔍 Data Isolation Example

### Scenario: Vendor A tries to access Vendor B's product

```
1. Route Check:  ✅ Authenticated (role = 4)

2. Scope Check:  Product::find($vendorB_id)
                 → VendorScope filters
                 → Returns NULL (not vendor A's product)

3. Policy Check: $this->authorize('update', null)
                 → Exception: Model not found

4. Result:       404 Not Found
```

---

## 📝 Model Relationships

```php
User (role_id = 4) {
    hasMany Products (vendor_id = user_id)
    hasMany Orders (through product orderItems)
}

Product {
    belongsTo User (vendor_id)
    hasMany OrderItems
    hasMany Reviews
}

Order {
    hasMany OrderItems
    hasMany OrderHistories (audit trail)
}

OrderItem {
    belongsTo Product
    belongsTo Order
}
```

---

## 🧪 Quick Test Examples

### Test Vendor Isolation

```php
// Login as Vendor A
Auth::login($vendorA);

// Try to access Vendor B's product
$vendorB_product = Product::where('vendor_id', $vendorB->id)->first();
// Returns: null (filtered by VendorScope)

// Try to update Vendor B's product
$this->authorize('update', Product::find($vendorB_product_id));
// Throws: AuthorizationException (403)
```

### Test Product Creation

```php
Auth::login($vendor);
$product = Product::create([
    'name' => 'Test',
    'vendor_id' => $vendor->id,  // ← Auto-assigned in controller
]);

// Verify vendor can access
$vendor->products()->find($product->id); // ✅ Found
```

### Test Order Access

```php
Auth::login($vendorA);

// Orders containing only vendorA's products
Order::all(); // Filtered by VendorOrderScope

// Try direct access to vendorB's order
Order::find($vendorB_order_id); // Returns null
```

---

## ⚙️ Configuration

### Role IDs

```php
[
    1 => 'admin',
    2 => 'staff',
    3 => 'customer',
    4 => 'vendor',  ← NEW
]
```

### Middleware

```php
// routes/web.php
Route::prefix('vendor')
    ->name('vendor.')
    ->middleware(['auth', 'role:vendor'])
    ->group(function () { ... });
```

### Policies

```php
// app/Providers/AuthServiceProvider.php
protected $policies = [
    Product::class => ProductPolicy::class,
    Order::class => OrderPolicy::class,
];
```

---

## 🔐 Admin Changes

### Sidebar Updates

**File**: `resources/views/admin/partials/sidebar.blade.php`

```blade
@role('admin')
    {{-- Reports section --}}
    {{-- Users section --}}
    {{-- System settings --}}
@endrole

{{-- E-commerce features (visible to both admin & staff) --}}
- Categories
- Products
- Orders
- Price Suggestions
```

### Vendor Cannot See

- ❌ User Management
- ❌ Global Reports
- ❌ System Settings
- ❌ Risk Rules
- ❌ Audit Logs

### Vendor Can See

- ✅ Dashboard (their stats)
- ✅ Products (their only)
- ✅ Orders (their only)
- ✅ Profile Settings

---

## 📋 Implementation Checklist

- [x] Created `layouts/vendor.blade.php`
- [x] Updated `admin/partials/sidebar.blade.php` with @role directives
- [x] Created `vendor/partials/sidebar.blade.php`
- [x] Created `vendor/partials/navbar.blade.php`
- [x] Created `vendor/dashboard.blade.php`
- [x] Created `vendor/products/index.blade.php`
- [x] Created `vendor/orders/index.blade.php`
- [x] Created `VendorDashboardController`
- [x] Created `VendorProductController`
- [x] Created `VendorOrderController`
- [x] Created `AuthServiceProvider` with Policies
- [x] Updated routes with vendor prefix
- [x] Enhanced User model with helper methods
- [x] Verified VendorScope is applied
- [x] Verified VendorOrderScope is applied

---

## 🚨 Important Notes

1. **Never trust `vendor_id` from request** - Always use `Auth::user()->id`
2. **Always authorize** - Use `$this->authorize()` before operations
3. **Test data isolation** - Run security tests before production
4. **VendorScope is automatic** - No need to add `where()` manually
5. **Audit trail** - All order status changes are logged

---

## 📞 Support References

- [Full Documentation](./VENDOR_PORTAL_IMPLEMENTATION.md)
- [Laravel Policies](https://laravel.com/docs/authorization)
- [Global Scopes](https://laravel.com/docs/eloquent#global-scopes)
- [Middleware](https://laravel.com/docs/middleware)

---

**Version**: 1.0  
**Last Updated**: January 24, 2026
