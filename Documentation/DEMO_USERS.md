# Demo Users Guide

## Tổng quan

File này chứa danh sách tất cả các tài khoản demo được tạo trong hệ thống E-commerce, với đầy đủ các role hiện có.

## Thông tin đăng nhập

**Mật khẩu chung cho tất cả tài khoản:** `password`

## Danh sách tài khoản theo Role

### 👑 Admin (2 tài khoản)

| Tên                        | Email             | Mô tả                |
| -------------------------- | ----------------- | -------------------- |
| Admin (từ AdminUserSeeder) | admin@example.com | Tài khoản admin gốc  |
| Admin Demo                 | admin@demo.com    | Tài khoản admin demo |

### 👨‍💼 Staff (1 tài khoản)

| Tên        | Email          | Mô tả                    |
| ---------- | -------------- | ------------------------ |
| Staff Demo | staff@demo.com | Tài khoản nhân viên demo |

### 🛒 Customer (6 tài khoản)

| Tên           | Email             | Số điện thoại |
| ------------- | ----------------- | ------------- |
| Customer Demo | customer@demo.com | +1-555-0103   |
| John Smith    | john@example.com  | +1-555-0201   |
| Sarah Johnson | sarah@example.com | +1-555-0202   |
| Mike Wilson   | mike@example.com  | +1-555-0203   |
| Emily Davis   | emily@example.com | +1-555-0204   |
| David Brown   | david@example.com | +1-555-0205   |

### 🏪 Vendor (4 tài khoản)

| Tên           | Email                  | Số điện thoại |
| ------------- | ---------------------- | ------------- |
| Vendor Demo   | vendor@demo.com        | +1-555-0104   |
| TechStore Pro | techstore@example.com  | +1-555-0301   |
| Fashion Hub   | fashionhub@example.com | +1-555-0302   |
| Home & Garden | homegarden@example.com | +1-555-0303   |

## Cách sử dụng

### Đăng nhập vào hệ thống

1. Truy cập trang đăng nhập: `http://localhost:8000/login`
2. Sử dụng email và mật khẩu từ danh sách trên
3. Mật khẩu chung: `password`

### Test các chức năng theo role

#### Admin

- Truy cập: `http://localhost:8000/admin`
- Quản lý users, products, orders, categories
- Xem báo cáo và thống kê

#### Staff

- Hỗ trợ khách hàng
- Quản lý đơn hàng
- Xem sản phẩm

#### Customer

- Duyệt sản phẩm: `http://localhost:8000/shop`
- Thêm vào giỏ hàng
- Đặt hàng và thanh toán
- Xem lịch sử đơn hàng: `http://localhost:8000/my-orders`

#### Vendor

- Quản lý sản phẩm của mình
- Xem đơn hàng
- Quản lý doanh thu

## Lưu ý

- Tất cả tài khoản đều có email đã được xác thực
- Status của tất cả users là 'active'
- Có thể sử dụng các tài khoản này để test đầy đủ chức năng của hệ thống

## Reset Database

Để reset lại database với dữ liệu demo:

```bash
php artisan migrate:fresh --seed
```
