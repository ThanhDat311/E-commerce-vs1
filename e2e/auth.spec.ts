import { test, expect } from '@playwright/test';

test.describe('Authentication & Role-Based Redirects', () => {
    test('login page loads correctly', async ({ page }) => {
        await page.goto('/login');
        await expect(page.getByLabel('Email address')).toBeVisible();
        await expect(page.getByLabel('Password')).toBeVisible();
        await expect(page.getByRole('button', { name: 'Log in' })).toBeVisible();
    });

    test('register page loads correctly', async ({ page }) => {
        await page.goto('/register');
        await expect(page.getByLabel('Full name')).toBeVisible();
        await expect(page.getByLabel('Email address')).toBeVisible();
        await expect(page.getByLabel('Password', { exact: true })).toBeVisible();
    });

    test('invalid login shows error', async ({ page }) => {
        await page.goto('/login');
        await page.getByLabel('Email address').fill('invalid@example.com');
        await page.getByLabel('Password').fill('wrongpassword');
        await page.getByRole('button', { name: 'Log in' }).click();

        // Should stay on login page with an error
        await expect(page).toHaveURL(/login/);
    });

    test('admin pages are protected', async ({ page }) => {
        await page.goto('/admin/products');
        // Should redirect to login
        await expect(page).toHaveURL(/login/);
    });

    test('guest is redirected from profile page', async ({ page }) => {
        await page.goto('/profile');
        await expect(page).toHaveURL(/login/);
    });
});
