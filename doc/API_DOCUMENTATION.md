# E-Commerce RESTful API v1 Documentation

## Overview

A comprehensive RESTful API built with Laravel and Laravel Sanctum for authentication. The API follows standard REST conventions with consistent JSON response formatting.

**Base URL:** `http://localhost:8000/api/v1`

**Current Version:** v1

---

## Response Format

### Success Response

```json
{
    "status": "success",
    "message": "Operation description",
    "data": {
        // Response data
    }
}
```

### Error Response

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        // Validation errors (optional)
    }
}
```

### Paginated Response

```json
{
    "status": "success",
    "message": "Success message",
    "data": [
        // Array of items
    ],
    "pagination": {
        "total": 50,
        "per_page": 15,
        "current_page": 1,
        "last_page": 4,
        "from": 1,
        "to": 15
    }
}
```

### HTTP Status Codes

- **200** - OK (Successful GET, PUT, PATCH)
- **201** - Created (Successful POST)
- **400** - Bad Request (Invalid input)
- **401** - Unauthorized (Missing/invalid token)
- **404** - Not Found (Resource doesn't exist)
- **422** - Unprocessable Entity (Validation error)
- **500** - Internal Server Error

---

## Authentication

### Using Sanctum Tokens

All protected endpoints require the `Authorization` header:

```
Authorization: Bearer {token}
```

**Token obtained from:**

- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`

---

## API Endpoints

### 1. Authentication Endpoints

#### Register User

- **Route:** `POST /api/v1/auth/register`
- **Auth:** ❌ Not required
- **Description:** Register a new user account

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890"
}
```

**Success Response (201):**

```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890"
        },
        "token": "1|abc123def456ghi789..."
    }
}
```

**Validation Errors (422):**

```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken"],
        "password": ["The password must be at least 8 characters"]
    }
}
```

---

#### Login User

- **Route:** `POST /api/v1/auth/login`
- **Auth:** ❌ Not required
- **Description:** Authenticate user and receive token

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Logged in successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890"
        },
        "token": "1|xyz789abc456def123..."
    }
}
```

**Error Response (401):**

```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

---

#### Get Current User

- **Route:** `GET /api/v1/auth/me`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Get authenticated user's details

**Success Response (200):**

```json
{
    "status": "success",
    "message": "User retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "created_at": "2026-01-24T10:30:00Z"
    }
}
```

---

#### Logout User

- **Route:** `POST /api/v1/auth/logout`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Logout user and revoke token

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Logged out successfully",
    "data": null
}
```

---

#### Refresh Token

- **Route:** `POST /api/v1/auth/refresh-token`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Get a new authentication token

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Token refreshed successfully",
    "data": {
        "token": "2|new_token_string..."
    }
}
```

---

### 2. Profile Endpoints

#### Get Profile

- **Route:** `GET /api/v1/profile`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Get authenticated user's profile with addresses

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Profile retrieved successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "created_at": "2026-01-24T10:30:00Z",
            "updated_at": "2026-01-24T10:30:00Z"
        },
        "addresses": [
            {
                "id": 1,
                "type": "home",
                "street": "123 Main St",
                "city": "Springfield",
                "state": "IL",
                "postal_code": "62701",
                "country": "USA",
                "is_default": true
            }
        ]
    }
}
```

---

#### Update Profile

- **Route:** `PUT /api/v1/profile`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Update user's profile information

**Request Body:**

```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "phone": "+0987654321"
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "phone": "+0987654321",
        "updated_at": "2026-01-24T11:00:00Z"
    }
}
```

---

#### Update Password

- **Route:** `POST /api/v1/profile/password`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Change user's password

**Request Body:**

```json
{
    "current_password": "old_password",
    "password": "new_password",
    "password_confirmation": "new_password"
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Password updated successfully",
    "data": null
}
```

---

#### Add Address

- **Route:** `POST /api/v1/profile/addresses`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Add a new address to profile

**Request Body:**

```json
{
    "type": "home",
    "street": "456 Oak Ave",
    "city": "Chicago",
    "state": "IL",
    "postal_code": "60601",
    "country": "USA",
    "is_default": false
}
```

**Success Response (201):**

```json
{
    "status": "success",
    "message": "Address added successfully",
    "data": {
        "id": 2,
        "type": "home",
        "street": "456 Oak Ave",
        "city": "Chicago",
        "state": "IL",
        "postal_code": "60601",
        "country": "USA",
        "is_default": false
    }
}
```

---

#### Update Address

- **Route:** `PUT /api/v1/profile/addresses/{addressId}`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `addressId` (path): Address ID to update
- **Description:** Update an existing address

**Request Body:**

```json
{
    "street": "789 Pine Rd",
    "city": "Boston",
    "state": "MA",
    "postal_code": "02101",
    "country": "USA"
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Address updated successfully",
    "data": {
        "id": 2,
        "type": "home",
        "street": "789 Pine Rd",
        "city": "Boston",
        "state": "MA",
        "postal_code": "02101",
        "country": "USA",
        "is_default": false
    }
}
```

---

#### Delete Address

- **Route:** `DELETE /api/v1/profile/addresses/{addressId}`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `addressId` (path): Address ID to delete
- **Description:** Delete an address from profile

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Address deleted successfully",
    "data": null
}
```

---

### 3. Cart Endpoints

#### List Cart

- **Route:** `GET /api/v1/cart`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Get user's shopping cart

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Cart retrieved successfully",
    "data": {
        "items": [
            {
                "product_id": 1,
                "product_name": "Laptop",
                "product_image": "/images/laptop.jpg",
                "price": 999.99,
                "quantity": 1,
                "subtotal": 999.99
            },
            {
                "product_id": 2,
                "product_name": "Mouse",
                "product_image": "/images/mouse.jpg",
                "price": 29.99,
                "quantity": 2,
                "subtotal": 59.98
            }
        ],
        "total_items": 2,
        "cart_total": 1059.97
    }
}
```

---

#### Add to Cart

- **Route:** `POST /api/v1/cart/add`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Add product to shopping cart

**Request Body:**

```json
{
    "product_id": 1,
    "quantity": 1
}
```

**Success Response (201):**

```json
{
    "status": "success",
    "message": "Product added to cart successfully",
    "data": {
        "product_id": 1,
        "product_name": "Laptop",
        "quantity": 1,
        "cart_total_items": 3
    }
}
```

**Error Response - Stock (400):**

```json
{
    "status": "error",
    "message": "Insufficient stock available"
}
```

---

#### Update Cart Item

- **Route:** `PUT /api/v1/cart/update/{productId}`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `productId` (path): Product ID in cart
- **Description:** Update product quantity in cart

**Request Body:**

```json
{
    "quantity": 2
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Cart updated successfully",
    "data": {
        "product_id": 1,
        "quantity": 2,
        "cart_total_items": 4
    }
}
```

---

#### Remove from Cart

- **Route:** `DELETE /api/v1/cart/remove/{productId}`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `productId` (path): Product ID to remove
- **Description:** Remove product from cart

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Product removed from cart successfully",
    "data": {
        "cart_total_items": 2
    }
}
```

---

#### Clear Cart

- **Route:** `DELETE /api/v1/cart/clear`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Clear all items from cart

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Cart cleared successfully",
    "data": null
}
```

---

#### Apply Coupon

- **Route:** `POST /api/v1/cart/coupon`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Description:** Apply discount coupon to cart

**Request Body:**

```json
{
    "coupon_code": "SUMMER2026"
}
```

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Coupon applied successfully",
    "data": {
        "coupon_code": "SUMMER2026",
        "discount": 50.0,
        "message": "Coupon applied"
    }
}
```

---

### 4. Order Endpoints

#### Get Order History

- **Route:** `GET /api/v1/orders`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Query Parameters:**
    - `page` (optional): Page number (default: 1)
    - `per_page` (optional): Items per page (default: 15)
- **Description:** Get paginated list of user's orders

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Order history retrieved successfully",
    "data": [
        {
            "id": 1,
            "order_number": "ORD-2026-00001",
            "status": "delivered",
            "total": 1500.0,
            "payment_status": "paid",
            "created_at": "2026-01-20T10:30:00Z"
        }
    ],
    "pagination": {
        "total": 5,
        "per_page": 15,
        "current_page": 1,
        "last_page": 1,
        "from": 1,
        "to": 5
    }
}
```

---

#### Get Order Details

- **Route:** `GET /api/v1/orders/{orderId}`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `orderId` (path): Order ID
- **Description:** Get detailed information about a specific order

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Order details retrieved successfully",
    "data": {
        "id": 1,
        "order_number": "ORD-2026-00001",
        "status": "delivered",
        "total": 1500.0,
        "subtotal": 1300.0,
        "tax": 130.0,
        "shipping_cost": 70.0,
        "payment_method": "credit_card",
        "payment_status": "paid",
        "notes": "Please handle with care",
        "created_at": "2026-01-20T10:30:00Z",
        "updated_at": "2026-01-24T15:00:00Z",
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product_name": "Laptop",
                "product_image": "/images/laptop.jpg",
                "quantity": 1,
                "price": 999.99,
                "subtotal": 999.99
            },
            {
                "id": 2,
                "product_id": 2,
                "product_name": "Mouse",
                "product_image": "/images/mouse.jpg",
                "quantity": 2,
                "price": 29.99,
                "subtotal": 59.98
            }
        ],
        "shipping_address": {
            "street": "123 Main St",
            "city": "Springfield",
            "state": "IL",
            "postal_code": "62701",
            "country": "USA"
        }
    }
}
```

---

#### Get Order Summary

- **Route:** `GET /api/v1/orders/{orderId}/summary`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `orderId` (path): Order ID
- **Description:** Get brief summary of an order

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Order summary retrieved successfully",
    "data": {
        "id": 1,
        "order_number": "ORD-2026-00001",
        "status": "delivered",
        "total": 1500.0,
        "payment_status": "paid",
        "created_at": "2026-01-20T10:30:00Z"
    }
}
```

---

#### Track Order

- **Route:** `GET /api/v1/orders/{orderId}/track`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `orderId` (path): Order ID
- **Description:** Get order tracking timeline

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Order tracking retrieved successfully",
    "data": {
        "order_number": "ORD-2026-00001",
        "current_status": "delivered",
        "timeline": [
            {
                "status": "pending",
                "timestamp": "2026-01-20T10:30:00Z",
                "notes": "Order received"
            },
            {
                "status": "confirmed",
                "timestamp": "2026-01-20T14:15:00Z",
                "notes": "Payment confirmed"
            },
            {
                "status": "shipped",
                "timestamp": "2026-01-21T08:00:00Z",
                "notes": "Package shipped"
            },
            {
                "status": "delivered",
                "timestamp": "2026-01-24T15:00:00Z",
                "notes": "Delivered successfully"
            }
        ]
    }
}
```

---

#### Cancel Order

- **Route:** `POST /api/v1/orders/{orderId}/cancel`
- **Auth:** ✅ Required (`auth:sanctum`)
- **Parameters:**
    - `orderId` (path): Order ID
- **Description:** Cancel a pending order

**Success Response (200):**

```json
{
    "status": "success",
    "message": "Order cancelled successfully",
    "data": {
        "id": 1,
        "status": "cancelled"
    }
}
```

**Error Response (400):**

```json
{
    "status": "error",
    "message": "Order cannot be cancelled in current status"
}
```

---

### 5. Health Check

#### API Health

- **Route:** `GET /api/health`
- **Auth:** ❌ Not required
- **Description:** Check if API is running

**Success Response (200):**

```json
{
    "status": "success",
    "message": "API is running",
    "version": "v1"
}
```

---

## Controllers

### 1. AuthController

**Location:** `app/Http/Controllers/Api/AuthController.php`

**Methods:**

- `register(Request $request)` - Register new user
- `login(Request $request)` - Authenticate user
- `logout(Request $request)` - Revoke token
- `me(Request $request)` - Get current user
- `refreshToken(Request $request)` - Get new token

**Middleware:** `auth:sanctum` (for logout, me, refreshToken)

---

### 2. ProfileController

**Location:** `app/Http/Controllers/Api/ProfileController.php`

**Methods:**

- `show(Request $request)` - Get user profile
- `update(Request $request)` - Update profile
- `updatePassword(Request $request)` - Change password
- `addAddress(Request $request)` - Add address
- `updateAddress(Request $request, int $addressId)` - Update address
- `deleteAddress(Request $request, int $addressId)` - Delete address

**Middleware:** `auth:sanctum` (all methods)

---

### 3. CartController

**Location:** `app/Http/Controllers/Api/CartController.php`

**Methods:**

- `list(Request $request)` - Get cart items
- `add(Request $request)` - Add to cart
- `update(Request $request, int $productId)` - Update quantity
- `remove(Request $request, int $productId)` - Remove item
- `clear(Request $request)` - Clear cart
- `applyCoupon(Request $request)` - Apply coupon

**Middleware:** `auth:sanctum` (all methods)

---

### 4. OrderController

**Location:** `app/Http/Controllers/Api/OrderController.php`

**Methods:**

- `history(Request $request)` - Get order history (paginated)
- `detail(Request $request, int $orderId)` - Get order details
- `summary(Request $request, int $orderId)` - Get order summary
- `track(Request $request, int $orderId)` - Track order
- `cancel(Request $request, int $orderId)` - Cancel order

**Middleware:** `auth:sanctum` (all methods)

---

## Middleware

### auth:sanctum

**Location:** `Illuminate\Auth\Middleware\Authenticate`

**Purpose:** Protect API endpoints that require authentication

**Usage:**

```php
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

**Bearer Token Header Required:**

```
Authorization: Bearer {token}
```

---

## Traits

### ApiResponse

**Location:** `app/Traits/ApiResponse.php`

**Methods:**

- `successResponse($data, $message, $status)` - Format success response
- `errorResponse($message, $status, $errors)` - Format error response
- `validationErrorResponse($errors, $message, $status)` - Format validation errors
- `paginatedResponse($data, $message, $status)` - Format paginated response

**Usage:**

```php
use App\Traits\ApiResponse;

class MyController extends Controller {
    use ApiResponse;

    public function show() {
        return $this->successResponse($data, 'Success message');
    }
}
```

---

## Error Handling

### Common Error Scenarios

#### 1. Missing Authentication Token

```json
{
    "status": "error",
    "message": "Unauthenticated"
}
```

**HTTP Status:** 401

---

#### 2. Validation Error

```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required"],
        "password": ["The password must be at least 8 characters"]
    }
}
```

**HTTP Status:** 422

---

#### 3. Resource Not Found

```json
{
    "status": "error",
    "message": "Resource not found"
}
```

**HTTP Status:** 404

---

#### 4. Invalid Request

```json
{
    "status": "error",
    "message": "Invalid request"
}
```

**HTTP Status:** 400

---

## Testing with Postman/cURL

### 1. Register User

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login User

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Get Profile (with token)

```bash
curl -X GET http://localhost:8000/api/v1/profile \
  -H "Authorization: Bearer 1|abc123def456ghi789..." \
  -H "Content-Type: application/json"
```

### 4. Add to Cart

```bash
curl -X POST http://localhost:8000/api/v1/cart/add \
  -H "Authorization: Bearer 1|abc123def456ghi789..." \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "quantity": 1
  }'
```

### 5. Get Orders

```bash
curl -X GET http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer 1|abc123def456ghi789..." \
  -H "Content-Type: application/json"
```

---

## API Version Strategy

- **Current Version:** v1 (under `/api/v1/`)
- **Future Versions:** v2, v3, etc. will use `/api/v2/`, `/api/v3/` prefixes
- **Backward Compatibility:** v1 will remain stable and supported

---

## Rate Limiting (Optional)

To implement rate limiting, add to `app/Http/Middleware/ThrottleRequests`:

```php
Route::middleware('throttle:60,1')->group(function () {
    // Rate limited routes
});
```

This limits users to 60 requests per minute.

---

## Deployment Checklist

- [ ] Sanctum configured in `config/sanctum.php`
- [ ] Database migrations run (`php artisan migrate`)
- [ ] API controllers placed in `app/Http/Controllers/Api/`
- [ ] Routes defined in `routes/api.php`
- [ ] ApiResponse trait imported in controllers
- [ ] Authentication tokens tested
- [ ] CORS configured if needed
- [ ] API documentation accessible

---

## Support & Troubleshooting

**Token Expired?**

- Use refresh endpoint: `POST /api/v1/auth/refresh-token`

**401 Unauthenticated?**

- Check `Authorization` header format
- Token must start with `Bearer `
- Ensure token hasn't expired

**422 Validation Error?**

- Check request body against endpoint documentation
- All required fields must be present
- Validate email format and password requirements

---

**Last Updated:** January 24, 2026
**API Version:** v1
**Status:** ✅ Production Ready
