# ELECTRO COMMERCE - DESIGN SYSTEM & ENGINEERING GUIDELINES

**Version:** 1.0.0
**Maintainer:** Tech Lead & Development Team
**Framework:** Laravel 10.x + Bootstrap 5 (Customized)

---

## 1. Core Principles (Nguyên tắc cốt lõi)

1.  **Consistency (Nhất quán):** Không "sáng tạo" màu sắc hay font chữ mới. Nếu cần thay đổi, phải cập nhật file này trước.
2.  **Component-Driven:** Ưu tiên sử dụng Laravel Blade Components (`x-components`) thay vì copy-paste HTML thuần.
3.  **Mobile-First:** Mọi giao diện phải hiển thị tốt trên Mobile trước khi tinh chỉnh cho Desktop.

---

## 2. Technology Stack (Công nghệ sử dụng)

- **Backend:** PHP 8.2+, Laravel Framework.
- **CSS Framework:** Bootstrap 5 (với biến CSS Custom Properties).
- **Icons:** FontAwesome 5 (Legacy) & Bootstrap Icons (Recommended).
- **JS:** Vanilla JS / jQuery (Legacy) -> Chuyển dịch dần sang Alpine.js cho các tương tác nhỏ.
- **Fonts:** Google Fonts (Open Sans, Roboto).

---

## 3. Visual Identity (Nhận diện hình ảnh)

Dựa trên file `layouts/master.blade.php` và `custom.css` hiện tại.

### 3.1 Color Palette (Bảng màu)

Sử dụng CSS Variables trong `:root` để dễ dàng thay đổi theme (Dark Mode sau này).

| Token Name              | Hex Code  | Usage (Cách dùng)                              |
| :---------------------- | :-------- | :--------------------------------------------- |
| `--color-primary`       | `#2563EB` | (Blue 600) Nút chính, Link, Brand Header       |
| `--color-primary-hover` | `#1D4ED8` | (Blue 700) Trạng thái Hover của nút chính      |
| `--color-secondary`     | `#1F2937` | (Gray 800) Footer, Text tiêu đề, Nút phụ       |
| `--color-accent`        | `#F97316` | (Orange 500) Giá khuyến mãi, Flash Sale, Badge |
| `--color-text-main`     | `#374151` | (Gray 700) Văn bản nội dung (Body text)        |
| `--color-text-light`    | `#6B7280` | (Gray 500) Văn bản phụ, caption, placeholder   |
| `--color-bg-light`      | `#F3F4F6` | (Gray 100) Background tổng thể                 |
| `--color-white`         | `#FFFFFF` | Background thẻ (Card), Modal                   |

### 3.2 Typography (Kiểu chữ)

- **Font Heading:** `Roboto`, sans-serif (Mạnh mẽ, hiện đại).
- **Font Body:** `Open Sans`, sans-serif (Dễ đọc).

| Scale     | Size (Desktop)  | Weight     | Line Height | Usage                                 |
| :-------- | :-------------- | :--------- | :---------- | :------------------------------------ |
| **H1**    | 32px (2rem)     | 700 (Bold) | 1.2         | Page Titles, Banner Hero              |
| **H2**    | 24px (1.5rem)   | 600 (Semi) | 1.3         | Section Titles (e.g., "Sản phẩm mới") |
| **H3**    | 20px (1.25rem)  | 600 (Semi) | 1.4         | Card Titles, Modal Titles             |
| **Body**  | 16px (1rem)     | 400 (Reg)  | 1.5         | Nội dung bài viết, mô tả              |
| **Small** | 14px (0.875rem) | 400 (Reg)  | 1.4         | Breadcrumb, Meta data                 |

---

## 4. UI Components (Thành phần giao diện)

### 4.1 Buttons (Nút bấm)

Tất cả button phải có `border-radius: 8px` (không dùng pill/tròn hoàn toàn trừ khi là tags).

- **Primary Button (`.btn-primary`):**
    - Bg: `--color-primary`
    - Text: White
    - Dùng cho: "Thêm vào giỏ", "Thanh toán", "Đăng nhập".
- **Outline Button (`.btn-outline-primary`):**
    - Border: `--color-primary`
    - Bg: Transparent
    - Dùng cho: "Xem chi tiết", "Quay lại".
- **Ghost Button:**
    - Bg: Transparent (Hover: Gray 100)
    - Dùng cho: Icon menu, Close modal.

### 4.2 Cards (Thẻ sản phẩm)

Quy định cứng cho `.product-item`:

- **Background:** White
- **Border:** 1px solid Gray 200
- **Radius:** 12px
- **Shadow:** `0 1px 3px rgba(0,0,0,0.1)` (Tăng lên khi Hover).
- **Image:** Aspect Ratio 1:1 (Vuông) hoặc 4:3. Object-fit: contain.

### 4.3 Forms (Biểu mẫu)

- **Input Height:** 45px (Touch-friendly).
- **Border:** 1px solid Gray 300. Focus: Border Blue 500 + Ring.
- **Label:** Luôn hiển thị trên Input (không dùng Placeholder thay Label).

---

## 5. Backend & Architecture Guidelines (Quy chuẩn Code Backend)

Đây là phần quan trọng nhất để giữ dự án Scalable.

### 5.1 Clean Architecture Layers

1.  **Models (`app/Models`):**
    - Chỉ chứa định nghĩa quan hệ (`hasMany`, `belongsTo`) và Accessors/Mutators.
    - **Cấm:** Viết Business Logic phức tạp (như tính toán thuế, xử lý thanh toán) tại đây.

2.  **Repositories (`app/Repositories`):**
    - Chịu trách nhiệm giao tiếp với Database.
    - Tên hàm phải rõ nghĩa: `findActiveProducts()`, `getOrdersByUser($userId)`.
    - Luôn trả về Collection hoặc Model, không trả về View.

3.  **Services (`app/Services`):**
    - Chứa Business Logic cốt lõi.
    - Ví dụ: `OrderService` sẽ gọi `ProductRepository` để kiểm tra tồn kho, sau đó gọi `PaymentGateway`, rồi mới lưu Order.

4.  **Controllers (`app/Http/Controllers`):**
    - **Nhiệm vụ:** Nhận Request -> Validate -> Gọi Service -> Trả về Response (View/JSON).
    - **Skinny Controllers:** Controller không được quá 100 dòng.

### 5.2 Coding Standards (Strict)

- **Naming:**
    - Variable: `$camelCase` (e.g., `$userProfile`).
    - Class: `PascalCase` (e.g., `ProductController`).
    - Database Table: `snake_case` (e.g., `product_categories`).
- **Type Hinting:** Bắt buộc sử dụng Type Hinting trong function arguments và return types.

    ```php
    // BAD
    public function getProduct($id) { ... }

    // GOOD
    public function getProduct(int $id): ProductDTO { ... }
    ```

- **Validation:** Tuyệt đối không validate trong Controller. Phải tạo `FormRequest` riêng (e.g., `StoreProductRequest`).
- **Security:** Luôn escape dữ liệu đầu ra `{{ $data }}`. Sử dụng `DB Transaction` cho các thao tác ghi dữ liệu liên quan đến tiền bạc/đơn hàng.

---

## 6. Git Workflow

- **Main Branch:** `main` (Chỉ chứa code production-ready).
- **Development Branch:** `develop` (Code đang phát triển).
- **Feature Branch:** `feature/ten-tinh-nang` (e.g., `feature/payment-integration`).
- **Commit Message:** `[Type]: Subject`
    - `feat`: Tính năng mới.
    - `fix`: Sửa lỗi.
    - `refactor`: Tối ưu code, không đổi logic.
    - `docs`: Viết tài liệu.
    - _Ví dụ:_ `feat: Add Momo payment gateway implementation`

---
