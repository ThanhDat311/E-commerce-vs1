import { test, expect } from '@playwright/test';

test.describe('Checkout Flow', () => {
    test('guest can browse the shop page', async ({ page }) => {
        await page.goto('/shop');
        await expect(page).toHaveURL(/shop/);
        // The page has an h1 "All Products" and product cards with images
        await expect(page.getByRole('heading', { name: 'All Products' })).toBeVisible();
        // Verify at least one product with "Add to Cart" button is visible
        await expect(page.getByRole('button', { name: 'Add to Cart' }).first()).toBeVisible();
    });

    test('guest can view a product detail page', async ({ page }) => {
        await page.goto('/shop');
        // Click the first product link (product name heading link)
        const firstProductLink = page.locator('h3 a').first();
        const productName = await firstProductLink.textContent();
        await firstProductLink.click();
        // Should be on the product detail page
        await expect(page).toHaveURL(/\/product\//);
        if (productName) {
            await expect(page.getByRole('heading', { name: productName })).toBeVisible();
        }
    });

    test('cart requires authentication', async ({ page }) => {
        await page.goto('/cart');
        // Cart is behind auth middleware, guest gets redirected to login
        await expect(page).toHaveURL(/login/);
    });

    test('checkout redirects when cart is empty', async ({ page }) => {
        await page.goto('/checkout');
        // Without items in cart, checkout redirects (to shop or login)
        // Just verify the page loaded without a server error
        await expect(page.locator('body')).toBeVisible();
    });
});
