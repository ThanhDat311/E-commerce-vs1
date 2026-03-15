# Báo cáo Kiểm thử: WebSocket Real-time Updates (FE-TC-007)

**Ngày thực hiện:** 12/03/2026
**Mô-đun:** Frontend (Vendor Dashboard), Backend (Admin Order Update), WebSockets (Laravel Reverb / Echo)
**Công cụ:** Playwright (End-to-End Testing)

---

## 1. Mục tiêu Kiểm thử (Test Objective)
Xác thực cơ chế **Real-time WebSockets** (thông qua Laravel Reverb) hoạt động ổn định trên Dashboard của Vendor, giúp cập nhật trạng thái đơn hàng ngay lập tức mà không cần Reload lại trang (F5).

Mục tiêu chính:
1. **Liên kết Reverb:** Đảm bảo kết nối Pusher/Reverb Channel giữa Client (Browser) và Server được thiết lập thành công.
2. **Song song hóa Event (Parallel Contexts):** Kiểm tra tính năng khi Agent A (Admin) kích hoạt hành động làm thay đổi State, thì Agent B (Vendor) ngay lập tức tiếp nhận broadcast payload và biến đổi DOM tương ứng.
3. **Reactive UI:** Trạng thái Order Pill (Badge) trên UI của Vendor tự động chuyển từ màu vàng (Pending) sang xanh lá cây (Shipped/Processing) thông qua Alpine.js/Blade + Echo listener.

## 2. Kịch bản Kiểm thử (Test Scenario)
File thi hành: `e2e/websocket-realtime.spec.ts`

**Các bước (Arrange - Act - Assert):**
1. **Arrange (Mở 2 Browser Contexts độc lập):**
   - **Context B (Vendor Browser):** Đăng nhập với tài khoản Vendor, điều hướng tới trang quản lý Đơn hàng (`/vendor/orders`). Vendor sẽ giữ nguyên cửa sổ này và đợi tín hiệu.
   - **Context A (Admin/System Browser - Ngầm):** Thiết lập một request ngầm dưới tư cách Admin để cập nhật một Order bất kì thuộc về Vendor này thành trạng thái `shipped` hoặc `processing`.
2. **Act:**
   - Playwright fire API POST/PATCH request từ Context A để cập nhật trạng thái đơn hàng. Hành động này sẽ kích hoạt tính năng Event Broadcasting của Laravel (ví dụ: `OrderStatusUpdated` event).
3. **Assert (Vendor UI Listen):**
   - Không thực hiện bất kỳ lệnh `page.reload()` nào trên Context B.
   - Sử dụng cơ chế `waitFor()` của Playwright để quét liên tục DOM của Vendor cho tới khi Component Badge/Label của Order ID tương ứng được thay đổi nội dung chữ thành `Shipped` (hoặc màu class Tailwind tương ứng).
   - Expected Result: Badge UI tự động thay đổi trong dưới mức **3000ms**, chứng minh đường truyền Socket thông suốt.

## 3. Kiến trúc Đề xuất & Setup Môi trường (Mentor's Note)

Bài test E2E cho WebSocket có độ phức tạp cao do yêu cầu cơ chế Asynchronous giữa 2 môi trường.

1. **Reverb Server:** Khi chạy bài Test này trên CI/CD, hãy đảm bảo worker `php artisan reverb:start` và `php artisan queue:work` đang chạy nền. Nếu thiếu Queue, Event sẽ không được broadcast lên Reverb.
2. **Echo Listener Setup:** Phía Frontend (Vendor UI) cần bảo đảm đã cấu hình Echo hook chuẩn chỉ:
```js
// Blade/Vite Frontend Example:
window.Echo.private(`vendor.orders.${vendorId}`)
    .listen('OrderStatusUpdated', (e) => {
        // Alpine.js magic:
        let orderRow = document.getElementById(`order-${e.order.id}`);
        let badge = orderRow.querySelector('.status-badge');
        badge.innerText = e.order.status;
        badge.className = `status-badge text-${e.color}-500 bg-${e.color}-100`;
    });
```
3. **Multi-Browser:** Kịch bản đã dùng tính năng cực mạnh của Playwright là `browser.newContext()` để sinh ra nhiều phiên ẩn danh (Incognito) song song, mô phỏng hoàn hảo 2 người dùng cách biệt địa lý tương tác với hệ thống.

---
*Báo cáo được tạo bởi tiến trình QA Plan.*
