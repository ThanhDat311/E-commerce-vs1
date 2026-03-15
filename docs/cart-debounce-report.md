# Báo cáo Kiểm thử: Cart Debouncing (FE-TC-006)

**Ngày thực hiện:** 12/03/2026
**Mô-đun:** Frontend (Cart / Checkout) & API (CartService)
**Công cụ:** Playwright (End-to-End Testing)

---

## 1. Mục tiêu Kiểm thử (Test Objective)
Xác thực cơ chế **Debouncing** của giỏ hàng khi người dùng thao tác tăng/giảm số lượng sản phẩm liên tục (spam clicks).

Mục tiêu chính:
1. Giao diện (UI) bằng Alpine.js không được bị treo (freeze) hoặc lag khi bấm liên tiếp.
2. Các request cập nhật giỏ hàng lên Server phải được gộp lại (Debounce) thay vì gửi 10 request riêng lẻ cho 10 cú click, giảm tải cho Database.
3. **Tính Đồng nhất Dữ liệu (Data Consistency):** Tổng số lượng cuối cùng hiển thị trên giao diện người dùng phải trùng khớp 100% với trạng thái dữ liệu (Session/Database) trên Backend.

## 2. Kịch bản Kiểm thử (Test Scenario)
File thi hành: `e2e/cart-debounce.spec.ts`

**Các bước (Arrange - Act - Assert):**
1. **Arrange:** Đăng nhập dưới quyền User. Truy cập trang `/shop`, thêm 1 sản phẩm đầu tiên vào giỏ hàng và chuyển hướng sang trang `/cart`.
2. **Act:** Sử dụng Playwright giả lập việc user click cực nhanh:
   - Click nút `+` (Tăng số lượng) **5 lần**, thời gian delay giữa các click là 100ms.
   - Click nút `-` (Giảm số lượng) **2 lần**, thời gian delay 100ms.
   - Tổng cộng (Net change): Số lượng giỏ hàng phải tăng thêm **+3**.
3. **Assert (UI):** Chờ 1.5 giây cho hiệu ứng Alpine.js Debounce kết thúc. Kiểm tra giá trị bên trong thẻ input `qty` có được cập nhật đúng là `Số lượng ban đầu + 3` hay không.
4. **Assert (Backend):** Sử dụng Playwright API Request (kế thừa Session Cookies) gọi ngầm API `/api/v1/cart`. Trích xuất mảng `cartItems` và đối chiếu xem Backend có đang ghi nhận đúng số lượng sản phẩm bằng với con số trên UI hay không.

## 3. Kiến trúc Đề xuất (Mentor's Note)

Để test script này hoạt động mượt mà và vượt qua thành công, phía Frontend (Blade/Alpine.js) cần có kiến trúc tham khảo sau:

```html
<!-- Cấu trúc HTML & Alpine.js đề xuất cho Cart Item -->
<div x-data="cartItem({ id: {{ $item['id'] }}, quantity: {{ $item['quantity'] }} })">
    <button @click="decrease" class="qty-decrease-btn">-</button>
    <input type="number" x-model="quantity" readonly class="cart-quantity-input">
    <button @click="increase" class="qty-increase-btn">+</button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartItem', (config) => ({
            id: config.id,
            quantity: config.quantity,
            
            // Hàm update lên server bị khóa (debounce) 500ms
            updateServer: updateServer: Alpine.debounce(function() {
                axios.patch(`/cart/update/${this.id}`, {
                    quantity: this.quantity
                }).then(res => {
                    // Update totals from response...
                });
            }, 500),

            increase() {
                this.quantity++;
                this.updateServer();
            },
            
            decrease() {
                if(this.quantity > 1) {
                    this.quantity--;
                    this.updateServer();
                }
            }
        }));
    });
</script>
```

Sức mạnh của `Alpine.debounce` giúp hệ thống không bị quá tải. Kịch bản E2E bằng Playwright đã được thiết kế chuyên biệt để bám sát và phát hiện ra các lỗi Race Condition nếu Frontend bỏ sót cơ chế debounce. 

---
*Báo cáo được khởi tạo tự động dựa trên Kiến trúc QA Plan.*
