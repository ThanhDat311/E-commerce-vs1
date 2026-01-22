# 🔧 CSRF 419 Error - Fix Documentation

## Vấn đề
Lỗi **419 Page Expired** khi submit form login (hoặc các form POST khác).

## Root Cause (Nguyên nhân gốc)
Theo phân tích theo **AI_RULES.md - STEP 2: ERROR & DEBUG MODE**:

1. **Thiếu Meta CSRF Token** trong layout `master.blade.php`
   - Laravel cần meta tag `<meta name="csrf-token">` để JavaScript có thể đọc token
   - Form có `@csrf` nhưng AJAX requests cần meta tag

2. **Thiếu jQuery AJAX Setup**
   - Các AJAX requests không tự động gửi CSRF token trong header
   - Cần setup `$.ajaxSetup()` để tự động thêm token

## Các Fix Đã Thực Hiện

### 1. ✅ Thêm Meta CSRF Token vào Layout
**File:** `resources/views/layouts/master.blade.php`

```php
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">  <!-- ✅ ĐÃ THÊM -->
    <title>@yield('title', 'Electro - Website Bán Hàng')</title>
    ...
</head>
```

### 2. ✅ Thêm jQuery AJAX Setup
**File:** `public/js/main.js`

```javascript
// CSRF Token Setup for AJAX requests
// Theo quy trình ELECTRO-SECURITY.md: Mọi form POST/PUT/DELETE bắt buộc có CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### 3. ✅ Thêm Debug Routes
**File:** `routes/web.php`

- `/test-csrf` - Kiểm tra CSRF token và session config
- `/debug-session` - Debug session chi tiết (chỉ khi `APP_DEBUG=true`)

## Kiểm Tra & Test

### Bước 1: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Bước 2: Kiểm tra Session
1. Truy cập: `http://localhost:8000/test-csrf`
2. Kiểm tra:
   - `csrf_token` có giá trị
   - `session_id` có giá trị
   - `session_driver` đúng (thường là `database` hoặc `file`)

### Bước 3: Test Login
1. Mở DevTools (F12) → Network tab
2. Submit form login
3. Kiểm tra Request Headers có:
   - `X-CSRF-TOKEN: <token>`
   - Cookie có session ID

### Bước 4: Debug Nếu Vẫn Lỗi
Truy cập: `http://localhost:8000/debug-session` (chỉ khi `APP_DEBUG=true`)

Kiểm tra:
- Session ID có tồn tại?
- CSRF token có giá trị?
- Session config có đúng?

## Các Nguyên Nhân Khác Có Thể Gây 419

### 1. Session Driver Configuration
**File:** `.env`
```env
SESSION_DRIVER=database  # hoặc 'file'
SESSION_LIFETIME=120
```

**Nếu dùng `database` driver:**
```bash
php artisan session:table
php artisan migrate
```

### 2. Session Cookie Domain
**File:** `config/session.php` hoặc `.env`
```env
SESSION_DOMAIN=  # Để trống cho localhost
```

### 3. Session Cookie Secure
**File:** `.env`
```env
SESSION_SECURE_COOKIE=false  # false cho HTTP localhost
```

### 4. Same-Site Cookie
**File:** `config/session.php`
```php
'same_site' => env('SESSION_SAME_SITE', 'lax'),  // 'lax' cho localhost
```

### 5. Session Expired
- Session lifetime quá ngắn
- Browser cache/cookies bị xóa
- Multiple tabs với session khác nhau

## Best Practices (Theo ELECTRO-SECURITY.md)

1. **Mọi form POST/PUT/DELETE** phải có `@csrf`
2. **AJAX requests** phải gửi `X-CSRF-TOKEN` header
3. **Meta tag** `<meta name="csrf-token">` trong layout
4. **Session security**: Đúng domain, secure, same-site

## Troubleshooting Commands

```bash
# Clear tất cả cache
php artisan optimize:clear

# Kiểm tra session table (nếu dùng database driver)
php artisan tinker
>>> session()->getId()
>>> session()->all()

# Test CSRF token
curl http://localhost:8000/test-csrf
```

## References

- [Laravel CSRF Protection](https://laravel.com/docs/12.x/csrf)
- [ELECTRO-SECURITY.md](./ELECTRO-SECURITY.md)
- [AI_RULES.md](./AI_RULES.md)

---

**Status:** ✅ Fixed  
**Date:** 2026-01-XX  
**Tested:** Login form, AJAX requests
