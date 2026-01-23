# API v1 Quick Reference Guide

## Endpoint Summary Table

| Method             | Endpoint                         | Auth | Controller        | Description       |
| ------------------ | -------------------------------- | ---- | ----------------- | ----------------- |
| **AUTHENTICATION** |
| POST               | `/api/v1/auth/register`          | ❌   | AuthController    | Register new user |
| POST               | `/api/v1/auth/login`             | ❌   | AuthController    | Login user        |
| GET                | `/api/v1/auth/me`                | ✅   | AuthController    | Get current user  |
| POST               | `/api/v1/auth/logout`            | ✅   | AuthController    | Logout user       |
| POST               | `/api/v1/auth/refresh-token`     | ✅   | AuthController    | Refresh token     |
| **PROFILE**        |
| GET                | `/api/v1/profile`                | ✅   | ProfileController | Get profile       |
| PUT                | `/api/v1/profile`                | ✅   | ProfileController | Update profile    |
| POST               | `/api/v1/profile/password`       | ✅   | ProfileController | Update password   |
| POST               | `/api/v1/profile/addresses`      | ✅   | ProfileController | Add address       |
| PUT                | `/api/v1/profile/addresses/{id}` | ✅   | ProfileController | Update address    |
| DELETE             | `/api/v1/profile/addresses/{id}` | ✅   | ProfileController | Delete address    |
| **CART**           |
| GET                | `/api/v1/cart`                   | ✅   | CartController    | List cart items   |
| POST               | `/api/v1/cart/add`               | ✅   | CartController    | Add to cart       |
| PUT                | `/api/v1/cart/update/{id}`       | ✅   | CartController    | Update quantity   |
| DELETE             | `/api/v1/cart/remove/{id}`       | ✅   | CartController    | Remove from cart  |
| DELETE             | `/api/v1/cart/clear`             | ✅   | CartController    | Clear cart        |
| POST               | `/api/v1/cart/coupon`            | ✅   | CartController    | Apply coupon      |
| **ORDERS**         |
| GET                | `/api/v1/orders`                 | ✅   | OrderController   | Order history     |
| GET                | `/api/v1/orders/{id}`            | ✅   | OrderController   | Order details     |
| GET                | `/api/v1/orders/{id}/summary`    | ✅   | OrderController   | Order summary     |
| GET                | `/api/v1/orders/{id}/track`      | ✅   | OrderController   | Track order       |
| POST               | `/api/v1/orders/{id}/cancel`     | ✅   | OrderController   | Cancel order      |
| **HEALTH**         |
| GET                | `/api/health`                    | ❌   | Built-in          | Health check      |

---

## Controller Location Reference

```
app/Http/Controllers/Api/
├── AuthController.php       (6 methods)
├── ProfileController.php    (6 methods)
├── CartController.php       (6 methods)
└── OrderController.php      (5 methods)
```

---

## Authentication Header Format

```
Authorization: Bearer {token}
```

**Example with cURL:**

```bash
curl -H "Authorization: Bearer 1|abc123..." http://localhost:8000/api/v1/profile
```

---

## Standard Response Structure

**Success (200, 201):**

```json
{
    "status": "success",
    "message": "Operation successful",
    "data": {
        /* ... */
    }
}
```

**Error (4xx, 5xx):**

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        /* optional validation errors */
    }
}
```

**Paginated (200):**

```json
{
    "status": "success",
    "message": "Success",
    "data": [
        /* ... */
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

---

## Quick Workflow Examples

### 1. User Registration → Login → Get Profile

```bash
# Step 1: Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
# Response includes: token

# Step 2: Login (alternative to register)
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

# Step 3: Use token to get profile
TOKEN="1|abc123..." # from response above
curl -X GET http://localhost:8000/api/v1/profile \
  -H "Authorization: Bearer $TOKEN"
```

### 2. Shopping Flow: Add Items → View Cart → Create Order

```bash
TOKEN="1|abc123..." # authenticated token

# Add products to cart
curl -X POST http://localhost:8000/api/v1/cart/add \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}'

curl -X POST http://localhost:8000/api/v1/cart/add \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 5, "quantity": 1}'

# View cart
curl -X GET http://localhost:8000/api/v1/cart \
  -H "Authorization: Bearer $TOKEN"

# Apply coupon
curl -X POST http://localhost:8000/api/v1/cart/coupon \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"coupon_code": "SUMMER2026"}'

# Proceed to checkout (separate endpoint in CheckoutController)
```

### 3. Order Management

```bash
TOKEN="1|abc123..." # authenticated token

# Get order history
curl -X GET http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer $TOKEN"

# Get specific order details
curl -X GET http://localhost:8000/api/v1/orders/1 \
  -H "Authorization: Bearer $TOKEN"

# Track order
curl -X GET http://localhost:8000/api/v1/orders/1/track \
  -H "Authorization: Bearer $TOKEN"

# Cancel order
curl -X POST http://localhost:8000/api/v1/orders/1/cancel \
  -H "Authorization: Bearer $TOKEN"
```

---

## Middleware Used

| Middleware     | Location                                         | Usage                                                    |
| -------------- | ------------------------------------------------ | -------------------------------------------------------- |
| `auth:sanctum` | `Illuminate\Auth\Middleware\Authenticate`        | Protects all authenticated endpoints                     |
| `api`          | `Illuminate\Routing\Middleware\ThrottleRequests` | Applied to all `/api/` routes (see RouteServiceProvider) |
| `cors`         | `Illuminate\Http\Middleware\HandleCors`          | Optional CORS support (if configured)                    |

---

## Validation Rules by Endpoint

### Register

- `name` - required, string, max:255
- `email` - required, email, unique
- `password` - required, min:8, confirmed
- `phone` - nullable, string, max:20

### Login

- `email` - required, email, exists
- `password` - required, min:8

### Update Profile

- `name` - sometimes, string, max:255
- `email` - sometimes, email, unique (except self)
- `phone` - sometimes, nullable, string, max:20

### Update Password

- `current_password` - required
- `password` - required, min:8, confirmed, different from current

### Add Address

- `type` - required, in:home,office,other
- `street` - required, string
- `city` - required, string
- `state` - required, string
- `postal_code` - required, string
- `country` - required, string
- `is_default` - boolean

### Add to Cart

- `product_id` - required, exists:products
- `quantity` - required, integer, min:1, max:100

### Update Cart Item

- `quantity` - required, integer, min:1, max:100

### Apply Coupon

- `coupon_code` - required, string

---

## HTTP Status Codes Used

| Code | Meaning              | Example                    |
| ---- | -------------------- | -------------------------- |
| 200  | OK                   | GET, PUT, PATCH successful |
| 201  | Created              | POST successful            |
| 400  | Bad Request          | Invalid input data         |
| 401  | Unauthorized         | Missing/invalid token      |
| 404  | Not Found            | Resource doesn't exist     |
| 422  | Unprocessable Entity | Validation failed          |
| 500  | Server Error         | Unexpected error           |

---

## Files Created/Modified

### New Files

- `app/Traits/ApiResponse.php` - Response formatting trait
- `app/Http/Controllers/Api/AuthController.php` - Authentication endpoints
- `app/Http/Controllers/Api/ProfileController.php` - Profile endpoints
- `app/Http/Controllers/Api/CartController.php` - Cart endpoints
- `app/Http/Controllers/Api/OrderController.php` - Order endpoints
- `routes/api.php` - API routes with v1 prefix
- `API_DOCUMENTATION.md` - Complete API documentation
- `API_QUICK_REFERENCE.md` - This file

### Modified Files

None - New API system created from scratch

---

## Usage in Frontend Applications

### JavaScript/Fetch API

```javascript
const token = localStorage.getItem("auth_token");

// Register
fetch("http://localhost:8000/api/v1/auth/register", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        name: "John",
        email: "john@example.com",
        password: "password123",
        password_confirmation: "password123",
    }),
})
    .then((r) => r.json())
    .then((data) => {
        localStorage.setItem("auth_token", data.data.token);
    });

// Get profile (with token)
fetch("http://localhost:8000/api/v1/profile", {
    method: "GET",
    headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
    },
})
    .then((r) => r.json())
    .then((data) => console.log(data.data));
```

### Vue.js/Axios

```javascript
import axios from "axios";

const api = axios.create({
    baseURL: "http://localhost:8000/api/v1",
});

// Add token to all requests
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Usage
api.get("/profile").then((r) => console.log(r.data));
```

---

## Development Tips

1. **Test Health Check First**

    ```bash
    curl http://localhost:8000/api/health
    ```

2. **Register Test User**

    ```bash
    curl -X POST http://localhost:8000/api/v1/auth/register \
      -H "Content-Type: application/json" \
      -d '{
        "name": "Test User",
        "email": "test@example.com",
        "password": "password123",
        "password_confirmation": "password123"
      }'
    ```

3. **Save Token for Testing**

    ```bash
    TOKEN="1|from_register_response"
    ```

4. **Use Token in All Requests**

    ```bash
    -H "Authorization: Bearer $TOKEN"
    ```

5. **Debug with Postman**
    - Create collection: E-Commerce API v1
    - Set variable: {{token}}
    - Use in Authorization header: Bearer {{token}}

---

## Troubleshooting

| Issue                | Solution                                                |
| -------------------- | ------------------------------------------------------- |
| 401 Unauthenticated  | Check Authorization header format: `Bearer {token}`     |
| 422 Validation Error | Review request body against docs, check required fields |
| 404 Not Found        | Verify endpoint path, check resource ID exists          |
| CORS Error           | Configure CORS in `config/cors.php`                     |
| Token Expired        | Use `/api/v1/auth/refresh-token` endpoint               |

---

**Last Updated:** January 24, 2026
**Status:** ✅ Ready for Development
