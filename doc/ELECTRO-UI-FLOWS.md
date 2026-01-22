# ELECTRO UI/UX & FLOWS SPECIFICATION

**Project:** Electro E-commerce
**Frontend:** Bootstrap 5 + Blade Templates
**Design System Reference:** `PROJECT-DESIGN-SYSTEM.md`

---

## 1. CUSTOMER JOURNEY (Luồng Khách Hàng)

### 1.1 Main Purchase Flow (Luồng Mua Hàng Chính)

**Flow A: Khám phá (Discovery)**

1. **Home (`/`)**: Hiển thị Banner Hero, "Sản phẩm mới" (Grid 4 cột), "Gợi ý cho bạn" (AI Section).
2. **Category (`/shop/{category}`)**: Sidebar lọc giá/thương hiệu bên trái, Grid sản phẩm bên phải.
3. **Product Detail (`/product/{slug}`)**:
    - Ảnh lớn bên trái, thông tin bên phải.
    - Nút **"Add to Cart"** (Primary Blue).
    - Phần **"Similar Products"** (AI generated) ở dưới cùng.

**Flow B: Thanh toán (Checkout)**

1. **Cart (`/cart`)**: Bảng danh sách item, cho phép sửa số lượng. Tổng tiền tạm tính.
2. **Checkout (`/checkout`)**:
    - Form thông tin giao hàng (Validate chặt chẽ).
    - Chọn phương thức thanh toán (COD trước, Online sau).
    - **Nút "Place Order"**: Khi bấm sẽ gọi API check tồn kho và AI Risk.
3. **Order Success (`/order-success`)**: Hiển thị Mã đơn hàng và Lời cảm ơn.

### 1.2 Edge Cases (Các trường hợp ngoại lệ)

- **Hết hàng (Out of Stock):** Disable nút "Add to Cart", đổi text thành "Hết hàng", hiển thị nút "Nhận thông báo".
- **Lỗi Validation:** Input viền đỏ (`.is-invalid`), hiển thị text lỗi màu đỏ (`.text-danger`) ngay dưới input.
- **AI Risk Block:** Nếu đơn hàng bị AI chặn, hiển thị Modal thông báo lịch sự: _"Đơn hàng cần xác minh thêm. Vui lòng liên hệ CSKH."_

---

## 2. ADMIN DASHBOARD UI (Giao diện Quản trị)

**Layout:** Sidebar cố định bên trái (`.bg-dark`), Header trắng, Content xám nhạt (`.bg-light`).

### 2.1 Dashboard Home

- **Stats Cards:** 4 thẻ trên cùng (Doanh thu ngày, Đơn mới, Tồn kho thấp, Cảnh báo rủi ro).
- **Charts:** Biểu đồ doanh thu 7 ngày gần nhất (Dùng thư viện Chart.js).

### 2.2 Order Management (`/admin/orders`)

- **Table:** Sticky header. Cột "Trạng thái" dùng Badge (`.badge`):
    - Pending: `.bg-warning`
    - Processing: `.bg-info`
    - Completed: `.bg-success`
    - Cancelled: `.bg-danger`
    - **Risk Flag:** Icon tam giác đỏ bên cạnh Mã đơn nếu AI đánh giá rủi ro cao.
- **Actions:** Nút "Xem chi tiết" (Icon mắt), "Hủy đơn" (Icon thùng rác - Yêu cầu Confirm Modal).

### 2.3 Product Management (`/admin/products`)

- **Form:** Layout 2 cột.
    - Trái: Tên, Mô tả (CKEditor), Giá.
    - Phải: Ảnh đại diện (Preview ngay khi upload), Danh mục, Tồn kho.

---

## 3. AI UI GUIDELINES (Giao diện AI)

Quy tắc hiển thị các tính năng thông minh trên giao diện Bootstrap 5:

1.  **Recommendation (Gợi ý):**
    - Luôn có tiêu đề rõ ràng: _"Có thể bạn sẽ thích"_ hoặc _"Sản phẩm tương tự"_.
    - Không được che lấp nút Mua hàng chính.

2.  **Risk Warning (Cảnh báo rủi ro - Admin Only):**
    - **Soft Alert:** Banner vàng (`.alert-warning`) trong chi tiết đơn hàng. _"Đơn hàng có dấu hiệu bất thường (Score: 75/100)."_
    - **Reasoning:** Hiển thị lý do dạng list:
        - _Địa chỉ IP mới._
        - _Giá trị đơn hàng lớn bất thường._

3.  **Fail-Open UX:**
    - Nếu AI Server lỗi, giao diện KHÔNG được hiện lỗi trắng trang.
    - Ẩn section gợi ý đi và hiển thị danh sách sản phẩm mặc định (Mới nhất/Bán chạy).
