# ELECTRO AI DECISION ENGINE ARCHITECTURE

**Component:** Business Logic Layer (Services)
**Responsibility:** Risk Assessment, Inventory Logic, Pricing Suggestions.

---

## 1. Architecture Overview

Hệ thống tuân thủ **Clean Architecture** của dự án Electro:

- **Input:** `CheckoutController` gọi `OrderService`.
- **Processing:** `OrderService` gọi `AIDecisionEngine` (Service).
- **Output:** Trả về `RiskResult` DTO (Data Transfer Object).

**Quy tắc Vàng:** `AIDecisionEngine` là **Stateless** (Không lưu trạng thái). Nó nhận dữ liệu -> Xử lý -> Trả kết quả. Không query DB trực tiếp trong logic tính toán.

---

## 2. Core Logic Components

### 2.1 Fraud Risk Assessment (Đánh giá Rủi ro)

**Đầu vào:**

- Thông tin đơn hàng (Tổng tiền, Số lượng).
- Thông tin User (Mới tạo? Guest? Lịch sử mua?).
- Context (IP, Thời gian đặt).

**Logic tính điểm (Score 0-100):**

- Base Score: 0
- +20: Guest Checkout.
- +25: Giá trị đơn > 5.000.000 VNĐ.
- +30: Đặt hàng từ 12:00 AM - 4:00 AM.
- +15: User mới tạo < 24h.

**Quyết định (Decision):**

- Score >= 80: `BLOCK` (Chặn đơn).
- Score 50-79: `FLAG` (Cho đặt nhưng gắn cờ cảnh báo Admin).
- Score < 50: `APPROVE`.

### 2.2 Inventory Handling (Xử lý Tồn kho)

**Quy trình chuẩn:**

1.  **Check:** Kiểm tra số lượng tồn (`$product->stock > $qty`).
2.  **Lock:** Khóa dòng dữ liệu trong DB (`lockForUpdate`).
3.  **Decrement:** Trừ tồn kho.
4.  **Rollback:** Nếu bất kỳ bước nào lỗi, hoàn tác toàn bộ Transaction.

---

## 3. Code Structure Reference

Cấu trúc file Backend đề xuất:
