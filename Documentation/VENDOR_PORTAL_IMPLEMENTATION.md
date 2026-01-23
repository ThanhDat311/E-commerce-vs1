# Vendor Portal Refactoring - Implementation Guide

**Date**: January 24, 2026  
**Status**: ✅ Complete Implementation  
**Version**: 1.0

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Directory Structure](#directory-structure)
3. [Permission Matrix](#permission-matrix)
4. [Architecture Components](#architecture-components)
5. [Implementation Details](#implementation-details)
6. [Security & Data Isolation](#security--data-isolation)
7. [Routes Reference](#routes-reference)
8. [Testing Checklist](#testing-checklist)

---

## Overview

The application has been refactored from a shared Dashboard to a dedicated **Vendor Portal** with strict data isolation, role-based access control, and vendor-specific views.

### Key Features:

- ✅ **Dedicated Vendor Layout** (`layouts/vendor.blade.php`)
- ✅ **Role-Based Sidebar** - Admin and Vendor sidebars are completely separate
- ✅ **Global VendorScope** - All queries automatically filtered by vendor_id for vendors
- ✅ **Vendor Routes** - Prefixed with `vendor/` with strict middleware
- ✅ **Data Isolation** - Vendors can only see/edit their own products and orders
- ✅ **Policy-Based Authorization** - Fine-grained control via Laravel Policies

---

## Directory Structure

### View Directory Structure

```
resources/views/
├── layouts/
│   ├── admin.blade.php          ✅ Admin/Staff shared layout
│   ├── vendor.blade.php         ✅ NEW: Vendor-only layout
│   ├── app.blade.php            (Guest layout)
│   ├── footer.blade.php
│   ├── header.blade.php
│   ├── master.blade.php
│   └── navigation.blade.php
│
├── admin/
│   ├── dashboard.blade.php
│   ├── partials/
│   │   ├── sidebar.blade.php    ✅ UPDATED: With @role directives
│   │   └── navbar.blade.php
│   ├── products/
│   ├── orders/
│   ├── categories/
│   ├── users/                   ✅ Admin-only section
│   ├── reports/                 ✅ Admin-only section
│   └── ...
│
└── vendor/                      ✅ NEW: Dedicated Vendor Portal
    ├── dashboard.blade.php      ✅ Vendor dashboard
    ├── partials/
    │   ├── sidebar.blade.php    ✅ Vendor-specific sidebar
    │   └── navbar.blade.php     ✅ Vendor-specific navbar
    ├── products/
    │   ├── index.blade.php      ✅ List vendor's products
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    └── orders/
        ├── index.blade.php      ✅ List vendor's orders
        └── show.blade.php
```

### Controller Structure

```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── OrderController.php
│   └── ...
│
└── Vendor/                      ✅ NEW: Dedicated Vendor Controllers
    ├── DashboardController.php  ✅ Vendor dashboard logic
    ├── ProductController.php    ✅ Vendor product management (vendor-scoped)
    └── OrderController.php      ✅ Vendor order management (vendor-scoped)
```

---

## Permission Matrix

### Access Control by Role

| Feature                      |  Admin   |  Staff   |        Vendor        |    Customer     |
| ---------------------------- | :------: | :------: | :------------------: | :-------------: |
| **Dashboard Access**         |    ✅    |    ✅    |          ✅          |       ❌        |
| **View All Products**        |    ✅    |    ✅    |          ❌          |       ✅        |
| **View Own Products**        |   N/A    |   N/A    |          ✅          |       N/A       |
| **Create Products**          |    ✅    |    ✅    |          ✅          |       ❌        |
| **Edit Products**            | ✅ (all) | ✅ (all) |    ✅ (own only)     |       ❌        |
| **Delete Products**          | ✅ (all) |    ❌    |    ✅ (own only)     |       ❌        |
| **View All Orders**          |    ✅    |    ✅    |          ❌          |       ❌        |
| **View Own Orders**          |   N/A    |   N/A    |    ✅ (vendor's)     | ✅ (customer's) |
| **Update Order Status**      |    ✅    |    ✅    | ✅ (own orders only) |       ❌        |
| **Manage Users**             |    ✅    |    ❌    |          ❌          |       ❌        |
| **View Reports**             |    ✅    |    ❌    |          ❌          |       ❌        |
| **View Global Reports**      |    ✅    |    ❌    |          ❌          |       ❌        |
| **Manage Categories**        |    ✅    |    ✅    |          ❌          |       ❌        |
| **Manage Price Suggestions** |    ✅    |    ❌    |          ❌          |       ❌        |
| **View Audit Logs**          |    ✅    |    ❌    |          ❌          |       ❌        |
| **Manage Risk Rules**        |    ✅    |    ❌    |          ❌          |       ❌        |

### User Roles

| Role ID | Name         | Description                                                 |
| ------- | ------------ | ----------------------------------------------------------- |
| 1       | **Admin**    | Full system access, manages users, reports, system settings |
| 2       | **Staff**    | Manage products, categories, orders (no user management)    |
| 3       | **Customer** | Browse products, make purchases, view own orders            |
| 4       | **Vendor**   | Manage own products and orders from their sales             |

---

## Architecture Components

### 1. Global Scopes (Data Isolation Layer)

#### VendorScope - Products Table

**File**: `app/Models/Scopes/VendorScope.php`

```php
// Applied to Product model
// When authenticated user is Vendor (role_id = 4):
// Query automatically filters: where vendor_id = auth()->user()->id
```

**Effect**:

```php
// For Admin/Staff:
Product::all() // Returns ALL products

// For Vendor (role_id = 4):
Product::all() // Returns ONLY products where vendor_id = auth()->id()
```

#### VendorOrderScope - Orders Table

**File**: `app/Models/Scopes/VendorOrderScope.php`

```php
// Applied to Order model
// When authenticated user is Vendor (role_id = 4):
// Query automatically filters: orders containing only their products
```

**Effect**:

```php
// For Admin/Staff:
Order::all() // Returns ALL orders

// For Vendor (role_id = 4):
Order::all() // Returns orders containing their products only
```

### 2. Layout System

#### Admin Layout

**File**: `resources/views/layouts/admin.blade.php`

- Shared between Admin and Staff roles
- Includes admin sidebar with role-based directives
- Full feature access

#### Vendor Layout

**File**: `resources/views/layouts/vendor.blade.php` ✅ NEW

- Dedicated to Vendor users
- Stripped-down interface (no user management, reports)
- Vendor-specific sidebar and navbar

### 3. Sidebar Role-Based Access

**Admin Sidebar**: `resources/views/admin/partials/sidebar.blade.php`

```blade
{{-- Reports Section - Admin Only --}}
@role('admin')
    <div class="sidebar-heading">Analytics</div>
    <li class="nav-item">
        <a href="{{ route('admin.reports.revenue') }}">Revenue</a>
    </li>
@endrole

{{-- System Section - Admin Only --}}
@role('admin')
    <div class="sidebar-heading">System</div>
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
@endrole
```

**Vendor Sidebar**: `resources/views/vendor/partials/sidebar.blade.php` ✅ NEW

```blade
<ul class="navbar-nav bg-white sidebar accordion" id="sidebar">
    <a class="sidebar-brand" href="{{ route('vendor.dashboard') }}">
        <i class="fas fa-store"></i>
        <div class="sidebar-brand-text">Vendor Portal</div>
    </a>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('vendor.products.index') }}">
            <i class="fas fa-box"></i>
            <span>My Products</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('vendor.orders.index') }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </li>
</ul>
```

### 4. Authorization Policies

**File**: `app/Policies/ProductPolicy.php`

```php
public function update(User $user, Product $product): bool
{
    return match ($user->role_id) {
        1 => true,                                    // Admin: all
        2 => true,                                    // Staff: all
        4 => $product->vendor_id === $user->id,      // Vendor: own only
        default => false,
    };
}
```

**File**: `app/Policies/OrderPolicy.php`

Similar pattern for order authorization.

### 5. Route Protection

**File**: `routes/web.php`

```php
// Vendor Routes - Prefixed with 'vendor/' and protected by middleware
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', VendorProductController::class);
    Route::controller(VendorOrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}/status', 'updateStatus')->name('update-status');
    });
});
```

---

## Implementation Details

### 1. Vendor Dashboard Controller

**File**: `app/Http/Controllers/Vendor/DashboardController.php`

```php
public function index()
{
    $vendor = Auth::user();

    // Get vendor's products (VendorScope applied automatically)
    $products = Product::where('vendor_id', $vendor->id)->get();

    // Get orders containing vendor's products
    $orders = Order::whereHas('orderItems.product', function ($query) use ($vendor) {
        $query->where('vendor_id', $vendor->id);
    })->latest()->take(5)->get();

    return view('vendor.dashboard', [
        'productsCount' => $products->count(),
        'lowStockCount' => $products->where('stock_quantity', '<', 10)->count(),
        'ordersCount' => Order::whereHas(...)->count(),
        'recentOrders' => $orders,
    ]);
}
```

### 2. Vendor Product Controller

**File**: `app/Http/Controllers/Vendor/ProductController.php`

Key features:

- ✅ `index()` - Shows only vendor's products (VendorScope applied)
- ✅ `create()` - Form for new product
- ✅ `store()` - Auto-assigns vendor_id to current user
- ✅ `edit()` - Uses `$this->authorize('update', $product)`
- ✅ `update()` - Ensures vendor_id cannot be changed
- ✅ `destroy()` - Vendors can only delete their own products

### 3. Vendor Order Controller

**File**: `app/Http/Controllers/Vendor/OrderController.php`

```php
public function index(Request $request)
{
    $vendor = Auth::user();

    // Get orders containing vendor's products (VendorOrderScope applied)
    $query = Order::whereHas('orderItems.product', function ($productQuery) use ($vendor) {
        $productQuery->where('vendor_id', $vendor->id);
    });

    // Apply filters...
    return view('vendor.orders.index', compact('orders'));
}

public function updateStatus(Request $request, $id)
{
    // Validate vendor can update this order
    $order = Order::with('orderItems.product')->findOrFail($id);

    $hasVendorProduct = $order->orderItems->some(function ($item) use ($vendor) {
        return $item->product->vendor_id === $vendor->id;
    });

    if (!$hasVendorProduct) {
        abort(403, 'Unauthorized');
    }

    // Update and log status change
    OrderHistory::create([...]);
    $order->update(['order_status' => $validated['order_status']]);
}
```

### 4. User Model Methods

**File**: `app/Models/User.php`

```php
public function hasRole(string $roleName): bool
{
    $roleIds = ['admin' => 1, 'staff' => 2, 'customer' => 3, 'vendor' => 4];
    return isset($roleIds[$roleName]) && $this->role_id === $roleIds[$roleName];
}

public function getRoleNameAttribute(): string
{
    $roles = [1 => 'admin', 2 => 'staff', 3 => 'customer', 4 => 'vendor'];
    return $roles[$this->role_id] ?? 'unknown';
}

public function isVendor(): bool
{
    return $this->role_id === 4;
}

public function products()
{
    return $this->hasMany(Product::class, 'vendor_id');
}
```

---

## Security & Data Isolation

### Multi-Layer Security Approach

#### Layer 1: Route Middleware

```php
Route::prefix('vendor')->middleware(['auth', 'role:vendor'])->group(...)
// Only authenticated vendors can access /vendor/* routes
```

#### Layer 2: Policy Authorization

```php
public function update(User $user, Product $product): bool
{
    return $product->vendor_id === $user->id;  // Explicit check
}
```

#### Layer 3: Global Scopes

```php
// VendorScope automatically filters queries
Product::all() // For vendor: only their products returned
```

#### Layer 4: Controller-Level Checks

```php
if (!$hasVendorProduct) {
    abort(403, 'Unauthorized to view this order');
}
```

### Data Flow Example

**Scenario**: Vendor A tries to view Vendor B's products

1. **Route Check**: ✅ Authenticated, role = 4 (vendor)
2. **Scope Check**: `Product::all()` returns only Vendor A's products
3. **Find Product**: `Product::find($vendorBProductId)` returns null (due to VendorScope)
4. **Result**: Product not found → 404 error

---

## Routes Reference

### Vendor Routes

| Method | Route                        | Name                          | Controller                         | Purpose        |
| ------ | ---------------------------- | ----------------------------- | ---------------------------------- | -------------- |
| GET    | `/vendor`                    | `vendor.dashboard`            | VendorDashboardController@index    | Dashboard      |
| GET    | `/vendor/products`           | `vendor.products.index`       | VendorProductController@index      | List products  |
| GET    | `/vendor/products/create`    | `vendor.products.create`      | VendorProductController@create     | Create form    |
| POST   | `/vendor/products`           | `vendor.products.store`       | VendorProductController@store      | Store product  |
| GET    | `/vendor/products/{id}/edit` | `vendor.products.edit`        | VendorProductController@edit       | Edit form      |
| PUT    | `/vendor/products/{id}`      | `vendor.products.update`      | VendorProductController@update     | Update product |
| DELETE | `/vendor/products/{id}`      | `vendor.products.destroy`     | VendorProductController@destroy    | Delete product |
| GET    | `/vendor/orders`             | `vendor.orders.index`         | VendorOrderController@index        | List orders    |
| GET    | `/vendor/orders/{id}`        | `vendor.orders.show`          | VendorOrderController@show         | View order     |
| PUT    | `/vendor/orders/{id}/status` | `vendor.orders.update-status` | VendorOrderController@updateStatus | Update status  |

### Admin Routes (Updated)

Reports and Users sections now have `@role('admin')` directives:

```blade
@role('admin')
    {{-- Reports section visible only to Admin --}}
    {{-- Users section visible only to Admin --}}
@endrole
```

---

## Testing Checklist

### ✅ Authentication Tests

- [ ] Admin user can access `/admin` routes
- [ ] Staff user can access `/admin` routes (with limited features)
- [ ] Vendor user can access `/vendor` routes
- [ ] Customer user cannot access `/admin` or `/vendor` routes
- [ ] Unauthenticated user redirected to login

### ✅ Data Isolation Tests

- [ ] Vendor A cannot see Vendor B's products
- [ ] Vendor A cannot see Vendor B's orders
- [ ] Vendor A cannot edit Vendor B's products
- [ ] Vendor A cannot delete Vendor B's products
- [ ] Admin can see all products and orders

### ✅ VendorScope Tests

```php
// Test 1: Admin views all products
Auth::login($admin);
$count = Product::count(); // Should include all vendors' products

// Test 2: Vendor views only own products
Auth::login($vendor);
$count = Product::count(); // Should include only their products
```

### ✅ Policy Tests

```php
// Test 1: Vendor can update own product
$this->assertTrue($vendor->can('update', $vendor->products()->first()));

// Test 2: Vendor cannot update other's product
$this->assertFalse($vendor->can('update', $otherVendor->products()->first()));

// Test 3: Admin can update any product
$this->assertTrue($admin->can('update', $vendor->products()->first()));
```

### ✅ UI Tests

- [ ] Admin sees "Users" menu item
- [ ] Admin sees "Reports" menu
- [ ] Vendor does NOT see "Users" menu item
- [ ] Vendor does NOT see "Reports" menu
- [ ] Vendor sidebar shows "My Products" instead of "Products"
- [ ] Vendor sidebar shows "Orders" (their orders only)
- [ ] Admin sidebar shows all menu items

### ✅ Route Tests

```bash
# Vendor accessing own routes
GET /vendor → 200 OK
GET /vendor/products → 200 OK

# Admin accessing admin routes
GET /admin → 200 OK
GET /admin/users → 200 OK

# Vendor accessing admin routes
GET /admin/users → 403 Forbidden

# Cross-vendor access
GET /vendor/products/vendorB_product_id/edit → 403 Forbidden
```

---

## Quick Start Commands

### Create a Test Vendor

```bash
# Via tinker
php artisan tinker
$vendor = User::create([
    'name' => 'Vendor Test',
    'email' => 'vendor@test.com',
    'password' => bcrypt('password'),
    'role_id' => 4,
]);

$vendor->products()->create([...]);
```

### Test Vendor Login

```bash
# Use Laravel Breeze or your auth system to login as vendor
# Then access: http://localhost:8000/vendor
```

### Database Commands

```bash
# Create vendor_id column if missing
php artisan migrate

# Seed vendors with products
php artisan db:seed VendorSeeder
```

---

## Migration Checklist

- [x] ✅ Create vendor.blade.php layout
- [x] ✅ Update admin sidebar with @role directives
- [x] ✅ Create vendor sidebar (separate views)
- [x] ✅ Create VendorDashboardController
- [x] ✅ Create VendorProductController
- [x] ✅ Create VendorOrderController
- [x] ✅ Create vendor view templates
- [x] ✅ Create AuthServiceProvider with Policies
- [x] ✅ Update routes with vendor prefix
- [x] ✅ Add User model helper methods
- [x] ✅ Verify VendorScope and VendorOrderScope are applied
- [x] ✅ Update Policies for vendor authorization

---

## Security Notes

1. **Never trust `vendor_id` in requests** - Always get it from `Auth::user()->id`
2. **Always authorize before operations** - Use `$this->authorize()` in controllers
3. **Use policies consistently** - Don't mix policy checks with inline authorization
4. **Test data isolation** - Verify vendors cannot access other vendors' data
5. **Audit trail** - OrderHistory model logs all status changes with actor ID

---

## Future Enhancements

- [ ] Vendor analytics dashboard (sales trends, top products)
- [ ] Vendor-specific reports (monthly revenue, order trends)
- [ ] Inventory management alerts
- [ ] Commission/payout management
- [ ] Vendor profile customization
- [ ] Product categorization per vendor
- [ ] Review/rating management for vendors

---

**Document Version**: 1.0  
**Last Updated**: January 24, 2026  
**Status**: ✅ Ready for Production
