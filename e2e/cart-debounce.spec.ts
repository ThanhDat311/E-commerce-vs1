import { test, expect } from '@playwright/test';

test.describe('Cart UI State Management', () => {
  test('FE-TC-006: Cart UI debounces rapid clicks and syncs correctly', async ({ page, request }) => {
    // 1. Arrange: Login as a demo user
    await page.goto('/login');
    await page.fill('input[name="email"]', 'john@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Wait for successful login redirect
    await expect(page).toHaveURL(/.*dashboard|.*shop|.*\//);

    // 2. Add an item to cart first
    await page.goto('/shop');
    
    // Find the first product "Add to Cart" button, assuming standard classes/markup
    const addToCartBtn = page.locator('.add-to-cart-btn').first();
    await addToCartBtn.waitFor({ state: 'visible' });
    await addToCartBtn.click();

    // 3. Navigate to Cart
    await page.goto('/cart');

    // Verify item is in cart and get initial quantity
    const quantityInput = page.locator('.cart-quantity-input').first();
    await quantityInput.waitFor({ state: 'visible' });
    const initialQtyStr = await quantityInput.inputValue();
    const initialQty = parseInt(initialQtyStr, 10) || 1;

    const increaseBtn = page.locator('.qty-increase-btn').first();
    const decreaseBtn = page.locator('.qty-decrease-btn').first();

    // 4. Act: Rapidly click increase and decrease within 2 seconds
    // Simulating user spam clicking (e.g. +5, -2 = net +3)
    let netChange = 0;
    
    for (let i = 0; i < 5; i++) {
      await increaseBtn.click();
      netChange++;
      await page.waitForTimeout(100); // 100ms gap
    }

    for (let i = 0; i < 2; i++) {
        await decreaseBtn.click();
        netChange--;
        await page.waitForTimeout(100); 
    }

    // 5. Assert (UI State)
    // Wait for the Alpine.js/Ajax debounce to settle. Usually 500ms - 1000ms.
    await page.waitForTimeout(1500); 

    const expectedUiQty = initialQty + netChange;
    const finalQtyStr = await quantityInput.inputValue();
    const finalQty = parseInt(finalQtyStr, 10);

    expect(finalQty).toBe(expectedUiQty);

    // 6. Assert (Backend State validation)
    // Fetch the raw cart API state to ensure the backend actually recorded the exact same final number
    // Getting session cookies from the browser context to make the API call authentic
    const cookies = await page.context().cookies();
    const apiContext = await request.newContext();
    await apiContext.addCookies(cookies);
    
    const cartResponse = await apiContext.get('/api/v1/cart');
    const cartData = await cartResponse.json();

    // The cart array can be structured depending on the CartService implementation.
    // Assuming cartItems is returning an array of product structures.
    const cartItems = cartData.cartItems || cartData.data?.cartItems || [];
    
    // Since we picked the "first()" on UI, we check if ANY item has the expected quantity 
    // (In a pristine test DB, there should only be 1 item anyway)
    const matchingBackendItem = cartItems.find(item => item.quantity === expectedUiQty);
    
    expect(matchingBackendItem).toBeDefined();
    expect(matchingBackendItem?.quantity).toBe(expectedUiQty);
  });
});
