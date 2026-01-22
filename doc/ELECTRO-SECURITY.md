# ELECTRO SECURITY PROTOCOLS

**Framework:** Laravel 10.x
**Auth:** Laravel Breeze / Custom Auth
**Middleware Strategy:** Role-Based Access Control (RBAC)

---

## 1. Authentication & Authorization

### 1.1 User Roles (Bảng `roles`)

- `customer`: Mua hàng, xem lịch sử đơn, quản lý profile.
- `admin`: Full quyền quản trị hệ thống.
- `staff`: Quản lý đơn hàng, kho (giới hạn quyền xóa).

### 1.2 Middleware

- `auth`: Bắt buộc đăng nhập.
- `CheckPermission`: Kiểm tra quyền cụ thể (ví dụ: `can:manage-products`).
- Tuyệt đối không hard-code check ID user (VD: `if ($user->id == 1)` là CẤM).

---

## 2. Order & Payment Security

### 2.1 Data Integrity

- **Giá sản phẩm:** Luôn lấy từ Database (`ProductRepository`) khi tính tổng tiền. KHÔNG tin tưởng dữ liệu gửi từ Client/Form.
- **Tồn kho:** Sử dụng `DB::beginTransaction()` và `lockForUpdate()` khi trừ kho để tránh Race Condition (nhiều người mua cùng lúc).

### 2.2 Payment Flow

1.  User bấm thanh toán -> Tạo Order trạng thái `PENDING`.
2.  Chuyển hướng sang Payment Gateway (nếu không phải COD).
3.  Gateway trả về Callback (Webhook).
4.  **Verify Signature:** Kiểm tra chữ ký điện tử (HMAC/Hash) từ Gateway để đảm bảo request là thật.
5.  Cập nhật trạng thái Order -> `PAID`.

---

## 3. Threat Mitigation (Chống tấn công)

- **XSS:** Blade engine tự động escape `{{ $data }}`. Với nội dung HTML (mô tả sản phẩm), sử dụng thư viện `HTMLPurifier` trước khi render `{!! $data !!}`.
- **CSRF:** Mọi form `POST`, `PUT`, `DELETE` bắt buộc có directive `@csrf`.
- **SQL Injection:** Sử dụng Eloquent ORM hoặc Query Builder Binding `where('id', $id)`. Không nối chuỗi SQL thuần.
- **Rate Limiting:** Áp dụng `Throttle` cho API Checkout và Login (VD: 5 lần sai pass trong 1 phút -> khóa IP tạm thời).

---

## 4. AI Security & Fail-Open

- **Nguyên tắc Fail-Open:** Nếu hệ thống AI (Risk Engine) bị lỗi hoặc timeout, **VẪN CHO PHÉP** khách đặt hàng bình thường. Log lỗi vào `laravel.log` để Admin kiểm tra sau.
- **Data Privacy:** Không gửi password, token thanh toán vào AI Engine để phân tích.
