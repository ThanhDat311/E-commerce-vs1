# Marketing Module - Quick Reference

## 🎯 Feature Overview

### Coupons

- Percentage or fixed amount discounts
- Date-based activation/expiration
- Global usage limits + per-user limits
- Minimum order requirements
- Real-time AJAX validation

### Flash Sales

- Time-limited product discounts
- Inventory/quantity tracking
- Real-time countdown timers
- Urgency indicators (red styling)
- One active sale per product

---

## 📁 File Locations

### Database

- `database/migrations/2026_01_24_create_coupons_table.php`
- `database/migrations/2026_01_24_create_user_coupons_table.php`
- `database/migrations/2026_01_24_create_flash_sales_table.php`

### Backend

- `app/Models/Coupon.php` - Coupon data model
- `app/Models/UserCoupon.php` - Usage history tracker
- `app/Models/FlashSale.php` - Flash sale data model
- `app/Services/CouponService.php` - Coupon logic
- `app/Services/FlashSaleService.php` - Sale logic
- `app/Traits/HasFlashSalePrice.php` - Product trait
- `app/Http/Controllers/Api/CouponController.php` - API endpoints

### Frontend

- `resources/views/components/coupon-input.blade.php` - AJAX coupon form
- `resources/views/components/flash-sale-badge.blade.php` - Sale badge with countdown
- `resources/js/countdown-timer.js` - Timer utility

---

## 🚀 Quick Setup

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Add Product Trait (if not done)

```php
// app/Models/Product.php
use App\Traits\HasFlashSalePrice;

class Product extends Model
{
    use HasFlashSalePrice; // ← Add this
}
```

### 3. Seed Demo Data

```php
// Create a coupon
Coupon::create([
    'code' => 'WELCOME10',
    'type' => 'percent',
    'value' => 10,
    'is_active' => true,
    'starts_at' => now(),
    'expires_at' => now()->addMonths(1),
]);

// Create a flash sale
FlashSale::create([
    'product_id' => 1,
    'sale_price' => 29.99,
    'quantity_limit' => 50,
    'starts_at' => now(),
    'ends_at' => now()->addHours(24),
    'is_active' => true,
]);
```

### 4. Add to Views

```blade
<!-- Product page -->
<x-flash-sale-badge :product="$product" />

<!-- Checkout page -->
<x-coupon-input />
```

### 5. Include JavaScript

```blade
<!-- In app layout -->
@vite(['resources/js/countdown-timer.js'])
```

---

## 🔌 API Endpoints

### Validate Coupon

```
POST /api/v1/coupons/validate
Content-Type: application/json

{
    "code": "WELCOME10",
    "order_total": 150.00
}

Response (success):
{
    "success": true,
    "message": "Coupon applied! You saved $15",
    "data": {
        "coupon_id": 1,
        "code": "WELCOME10",
        "discount_type": "percent",
        "discount_value": 10,
        "discount_amount": 15,
        "original_total": 150,
        "final_total": 135
    }
}
```

### Get Coupon History

```
GET /api/v1/coupons/history
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "coupon_code": "WELCOME10",
            "discount_amount": 15,
            "used_at": "Jan 24, 2025 10:30",
            "order_id": 123
        }
    ]
}
```

### Check Coupon Usage

```
POST /api/v1/coupons/check-usage
Authorization: Bearer {token}

{
    "coupon_id": 1
}

Response:
{
    "success": true,
    "data": {
        "has_used": false
    }
}
```

---

## 💻 Service Usage

### CouponService

```php
$couponService = app(CouponService::class);

// Validate coupon
$result = $couponService->validateCoupon('WELCOME10', 150.00, $userId);
// Returns: ['valid' => bool, 'error' => string, 'data' => array]

// Apply coupon
$couponService->applyCoupon($couponId, $userId, $discountAmount);

// Check if user used coupon
$used = $couponService->hasUserUsedCoupon($couponId, $userId);

// Get user history
$history = $couponService->getUserCouponHistory($userId);
```

### FlashSaleService

```php
$flashSaleService = app(FlashSaleService::class);

// Get active sale for product
$sale = $flashSaleService->getActiveFlashSale($productId);

// Get effective price (sale or regular)
$price = $flashSaleService->getEffectivePrice($product);

// Get discount percentage
$percent = $flashSaleService->getDiscountPercentage($product);

// Get countdown
$seconds = $flashSaleService->getTimeRemaining($product);
$formatted = $flashSaleService->getFormattedCountdown($product);

// Record sale (when order placed)
$flashSaleService->recordSale($productId, $quantity);

// Get all active sales
$sales = $flashSaleService->getActiveFlashSales($limit = 10);
```

---

## 🎨 Frontend Examples

### Using Coupon Component

```blade
<div class="checkout-form">
    <!-- ... other checkout fields ... -->

    <x-coupon-input />

    <button type="submit">Complete Purchase</button>
</div>
```

### Using Sale Badge

```blade
<!-- Product card -->
<div class="product-card">
    <img src="{{ $product->image_url }}">
    <h3>{{ $product->name }}</h3>

    <!-- Shows badge if sale is active -->
    <x-flash-sale-badge :product="$product" />

    <button>Add to Cart</button>
</div>
```

### JavaScript Events

```javascript
// Listen for coupon applied
window.addEventListener("coupon-applied", (e) => {
    console.log("Coupon:", e.detail.code);
    console.log("Discount:", e.detail.discountAmount);
    console.log("Total:", e.detail.finalTotal);
    // Update cart UI
});

// Listen for coupon removed
window.addEventListener("coupon-removed", () => {
    console.log("Coupon removed");
    // Reset cart UI
});
```

---

## 📊 Model Accessors (on Product)

```php
$product->price                   // Regular price (unchanged)
$product->effective_price         // Sale price if active, else regular
$product->sale_price              // Sale price (null if no sale)
$product->discount_percentage     // % off (null if no sale)
$product->time_remaining          // Seconds until sale ends
$product->formatted_countdown     // "2h 15m 30s"
$product->is_on_sale              // Boolean
```

---

## 🔒 Permissions & Auth

### Public Endpoints

- `POST /api/v1/coupons/validate` - Anyone can validate (rate-limit recommended)

### Protected Endpoints (auth:sanctum)

- `GET /api/v1/coupons/history` - User's coupon history
- `POST /api/v1/coupons/check-usage` - User's usage check

---

## 🧪 Testing Examples

### Test Coupon Validation

```php
$coupon = Coupon::create([
    'code' => 'TEST10',
    'type' => 'percent',
    'value' => 10,
    'is_active' => true,
]);

$result = $this->postJson('/api/v1/coupons/validate', [
    'code' => 'TEST10',
    'order_total' => 100,
]);

$result->assertStatus(200)
    ->assertJson(['success' => true]);
```

### Test Flash Sale Price

```php
$product = Product::factory()->create(['price' => 100]);
$sale = FlashSale::create([
    'product_id' => $product->id,
    'sale_price' => 79.99,
    'starts_at' => now(),
    'ends_at' => now()->addHour(),
    'is_active' => true,
]);

$this->assertEquals(79.99, $product->effective_price);
$this->assertTrue($product->is_on_sale);
```

---

## ⚡ Performance Tips

### Cache Active Sales

```php
$sales = Cache::remember('flash_sales_active', 3600, function() {
    return FlashSaleService::getActiveFlashSales();
});
```

### Use Indexes

- Already created on migrations
- Query performance optimized

### Rate Limit Coupon API

```php
// In middleware or route
Route::post('validate', [CouponController::class, 'validate'])
    ->middleware('throttle:60,1'); // 60 requests per minute
```

---

## 🐛 Debugging

### Check Active Sales

```php
$sales = FlashSale::where('is_active', true)
    ->where('starts_at', '<=', now())
    ->where('ends_at', '>', now())
    ->with('product')
    ->get();
```

### Check Coupon Usage

```php
$usage = UserCoupon::where('coupon_id', $couponId)
    ->where('user_id', $userId)
    ->get();
```

### Test Effective Price

```php
$product = Product::find(1);
dd([
    'regular_price' => $product->price,
    'effective_price' => $product->effective_price,
    'is_on_sale' => $product->is_on_sale,
    'discount' => $product->discount_percentage,
]);
```

---

## 🔄 Discount Calculation Order

1. **FIRST:** Flash Sale (changes product unit price)
    - Product price → sale price if active
    - Applied to each line item

2. **SECOND:** Coupon (applied to final total)
    - Calculated after all items priced
    - Takes percentage/fixed from subtotal

3. **Result:** Cart total = (items with sale prices) + shipping - coupon

**Example:**

```
Item 1: Regular $100 → Sale $79.99 (with flash sale)
Item 2: Regular $50 → Regular $50 (no sale)
Subtotal: $129.99
Coupon (10%): -$13.00
Final Total: $116.99
```

---

## 📋 Checklist for Implementation

- [ ] Run migrations: `php artisan migrate`
- [ ] Add HasFlashSalePrice trait to Product model
- [ ] Create demo coupons and flash sales in seeder
- [ ] Test API endpoints with Postman
- [ ] Add components to relevant pages
- [ ] Include countdown-timer.js in layout
- [ ] Test with real products
- [ ] Verify backward compatibility
- [ ] Load test with concurrent coupon validations
- [ ] Set up scheduler for auto-deactivating expired sales

---

## 📞 Support

All components follow the established Service+Repository architecture:

- Business logic in **Services** (CouponService, FlashSaleService)
- Data handling in **Models** (Coupon, UserCoupon, FlashSale)
- API in **Controllers** (CouponController)
- UI in **Views** (components)

No business logic in controllers ✓
