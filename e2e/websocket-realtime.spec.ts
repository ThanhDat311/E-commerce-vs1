import { test, expect } from '@playwright/test';

test.describe('Real-time WebSockets (Laravel Reverb)', () => {
  
  // Notice: This test requires 2 simulated users (Admin modifying an order, and Vendor watching the dashboard)
  test('FE-TC-007: Vendor Dashboard updates order status in real-time without reloading', async ({ browser, request }) => {
    
    // ---------------------------------------------------------
    // 1. Arrange: Setup Vendor Window (Context A)
    // ---------------------------------------------------------
    const vendorContext = await browser.newContext();
    const vendorPage = await vendorContext.newPage();

    // Login as Demo Vendor
    await vendorPage.goto('/login');
    await vendorPage.fill('input[name="email"]', 'vendor@example.com'); // Thay bằng email demo của hệ thống
    await vendorPage.fill('input[name="password"]', 'password');
    await vendorPage.click('button[type="submit"]');

    // Wait for auth to redirect
    await expect(vendorPage).toHaveURL(/.*dashboard|.*vendor/);
    
    // Navigate to Vendor Orders page
    // Ensure the vendor has at least one 'Pending' order for testing. We'll pick the first one.
    await vendorPage.goto('/vendor/orders');

    // Mốc thời gian chờ WebSocket connect
    await vendorPage.waitForTimeout(2000); // 2 giây để Echo khởi tạo xong

    // Identify a pending order row
    const firstOrderRow = vendorPage.locator('.order-row').first(); // Giả định row class là .order-row
    await firstOrderRow.waitFor({ state: 'visible' });

    // Trích xuất Order ID (thường lấy qua attribute ví dụ: data-order-id="15")
    const orderIdStr = await firstOrderRow.getAttribute('data-order-id');
    const orderId = orderIdStr ? parseInt(orderIdStr) : 1; 

    const statusBadge = firstOrderRow.locator('.status-badge');
    
    // Đảm bảo trạng thái hiện tại là Pending hoặc một state ban đầu
    const initialStatus = await statusBadge.innerText();

    // ---------------------------------------------------------
    // 2. Arrange: Setup Admin Context (Context B - Headless API action)
    // ---------------------------------------------------------
    // We will bypass a full Admin UI login and just use an API Context. 
    // We'll login to get Sanctum/Session cookies for the Admin.
    const adminContext = await browser.newContext();
    const adminPage = await adminContext.newPage();
    
    await adminPage.goto('/login');
    await adminPage.fill('input[name="email"]', 'admin@example.com');
    await adminPage.fill('input[name="password"]', 'password');
    await adminPage.click('button[type="submit"]');
    await adminPage.waitForURL(/.*admin/); // Wait for admin auth

    const adminCookies = await adminContext.cookies();
    const adminApiContext = await request.newContext();
    await adminApiContext.addCookies(adminCookies);

    // ---------------------------------------------------------
    // 3. Act: Admin fires API to update Order Status
    // ---------------------------------------------------------
    // Admin sets order to "Shipped"
    const newStatus = 'shipped';
    const updateResponse = await adminApiContext.patch(`/api/v1/vendor/orders/${orderId}/status`, {
      data: {
        status: newStatus
      }
    });

    // Check action successful
    // *Lưu ý: Route này có thể phải sửa theo chuẩn endpoint API Admin trong routes
    expect(updateResponse.ok()).toBeTruthy(); 

    // ---------------------------------------------------------
    // 4. Assert: Vendor UI automatically catches event and updates
    // ---------------------------------------------------------
    // TRỌNG TÂM E2E WEBSOCKET: Chúng ta chờ text bên trong Badge ở trang Vendor 
    // thay đổi mà không gọi reload().
    
    // Chúng ta kì vọng nội dung chữ đổi qua "shipped" hoặc chứa chuỗi tương đương (do Tailwind UpperCase/Title)
    await expect(statusBadge).toHaveText(/Shipped|shipped/i, { timeout: 4000 }); // Chờ tối đa 4 giây để event nổ
    
    // Xác minh rằng Browser Vendor *không hề* bị F5/Navigated (Có thể bọc kĩ càng thông qua việc kiểm tra page.isClosed())
    
    // Cleanup Contexts
    await vendorContext.close();
    await adminContext.close();
  });
});
