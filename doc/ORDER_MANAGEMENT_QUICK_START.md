# Order Management UI - Quick Start Guide

**Last Updated:** January 28, 2026  
**Status:** Ready to Deploy

---

## 🚀 Quick Start

### For Admins/Staff

#### Accessing Order Management

```
1. Log in to admin dashboard
2. Click "Orders" in sidebar
3. View list of all orders
```

#### Searching for Orders

```
1. Enter customer name or order ID in search box
2. Click "Filter" button
3. Results update automatically
```

#### Filtering Orders

```
By Date:
  1. Click "From" date field
  2. Select start date
  3. Click "To" date field
  4. Select end date
  5. Click "Filter"

By Payment Method:
  1. Click Payment Method dropdown
  2. Select method (Credit Card, PayPal, etc.)
  3. Click "Filter"
```

#### Viewing Order Details

```
1. Click order ID (e.g., #ORD-7721) or "View Details" button
2. Left side shows order items and payment info
3. Right side shows customer info and order total
```

#### Updating Order Status

```
1. Click "Update Status" button (blue)
2. Select new status from dropdown
3. Optionally add admin note
4. Click "Update Status"
5. Page refreshes with success message
```

#### Cancelling an Order

```
1. Scroll down to "Danger Zone" section
2. Click "Cancel Order" button (red)
3. Review warning message
4. Click "Yes, Cancel Order" to confirm
```

#### Printing Invoice

```
1. Click "Print Invoice" button
2. Print preview opens
3. Click print or save as PDF
```

#### Resending Confirmation

```
1. Click "Resend Confirmation" button
2. Email sent to customer
3. Success message displayed
```

---

## 👨‍💻 For Developers

### Setup

1. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

2. **Build Assets**

    ```bash
    npm run build  # Production
    npm run dev    # Development
    ```

3. **Clear Cache**
    ```bash
    php artisan cache:clear
    ```

### File Locations

```
Views:
  - resources/views/admin/orders/index.blade.php
  - resources/views/admin/orders/show.blade.php

Controller:
  - app/Http/Controllers/Admin/OrderController.php

Styling:
  - public/css/admin-custom.css

Documentation:
  - Documentation/ORDER_MANAGEMENT_UI_REFACTOR.md
  - Documentation/ORDER_MANAGEMENT_VISUAL_GUIDE.md
  - Documentation/ORDER_MANAGEMENT_VALIDATION.md
```

### Common Tasks

#### Adding a New Filter

```php
// In OrderController.php index() method
if ($request->filled('new_filter')) {
    $query->where('field', $request->new_filter);
}
```

#### Styling a New Component

```css
/* In admin-custom.css */
.new-component {
    background-color: var(--brand-primary);
    border-radius: 0.375rem;
    padding: 1rem;
}
```

#### Modifying Status Badges

```blade
<!-- In index.blade.php -->
@php
    $statusConfig = [
        'new_status' => ['badge' => 'bg-color', 'label' => 'Label'],
    ];
@endphp
```

---

## 🐛 Troubleshooting

### Issue: Orders Not Loading

**Solution:**

```bash
1. Verify database connection
2. Check orders table exists: php artisan tinker
   > Order::count()
3. Clear cache: php artisan cache:clear
```

### Issue: Filters Not Working

**Solution:**

1. Check URL parameters: ?keyword=...&date_from=...
2. Verify input fields have correct names
3. Clear form cache in browser

### Issue: Modals Not Appearing

**Solution:**

1. Verify Bootstrap JS is loaded
2. Check browser console for errors
3. Hard refresh browser (Ctrl+Shift+R)

### Issue: Styling Not Applied

**Solution:**

```bash
1. npm run build
2. Clear browser cache
3. Check CSS file is loaded
```

### Issue: Status Update Not Saving

**Solution:**

1. Verify order_status column exists in orders table
2. Check OrderHistory table exists
3. Verify CSRF token is present in form

---

## 📊 Key Data Displayed

### Order List

- Order ID (clickable)
- Customer name + email
- Order date and time
- Total amount
- Payment method with icon
- Order status (color badge)
- View details button

### Order Detail

- All order items with prices
- Product images and SKUs
- Customer contact information
- Shipping address
- Payment transaction details
- Order summary with totals
- Order status and history

---

## 🔐 Access Control

**Who can access:**

- Admin (role_id = 1): ✅ Full access
- Staff (role_id = 2): ✅ Full access
- Vendor (role_id = 4): ✅ Limited to their orders
- Customer (role_id = 3): ❌ No access

**Route Protection:**

```php
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('orders', AdminOrderController::class);
});
```

---

## 📱 Responsive Behavior

### Desktop (≥992px)

- Two-column layout
- Full feature set
- Wide tables

### Tablet (768-991px)

- Adjusted spacing
- Single column option
- Readable tables

### Mobile (<768px)

- Full width
- Single column
- Optimized touch targets

---

## 🔍 Status Values

```
Pending      → Order received, awaiting processing
Processing   → Order being prepared
Shipped      → Order on way to customer
Completed    → Order delivered
Cancelled    → Order cancelled
```

## 💳 Payment Methods

```
credit_card      → Credit Card (💳 icon)
paypal           → PayPal (🅿️ icon)
bank_transfer    → Bank Transfer (🏦 icon)
cod              → Cash on Delivery (🚚 icon)
```

---

## 📈 Performance Tips

### For Faster Loading

1. Use pagination (already implemented)
2. Filter before searching large datasets
3. Use date range to narrow results
4. Index database columns (already optimized)

### For Better Experience

1. Use modern browsers (Chrome, Firefox, Safari)
2. Enable JavaScript
3. Allow third-party cookies for modals
4. Use high-speed internet connection

---

## 🎯 Best Practices

### When Updating Orders

1. Always add admin notes explaining changes
2. Verify customer email before marking shipped
3. Check payment status before updating order status
4. Review order items before processing

### When Cancelling Orders

1. Verify order isn't already completed
2. Notify customer if appropriate
3. Note reason in admin notes
4. Check for refund requirements

### When Searching

1. Use exact order ID for quick results
2. Use partial customer name for broader results
3. Use date range to filter by period
4. Combine filters for precise results

---

## 📞 Getting Help

### Documentation

- Full Guide: `ORDER_MANAGEMENT_UI_REFACTOR.md`
- Visual Guide: `ORDER_MANAGEMENT_VISUAL_GUIDE.md`
- Validation: `ORDER_MANAGEMENT_VALIDATION.md`

### In-App Help

- Hover over buttons for tooltips
- Check modal headers for context
- Review error messages carefully

### For Developers

- Check inline code comments
- Review Laravel documentation
- Look at related migrations
- Examine model relationships

---

## ✅ Common Tasks Checklists

### Daily Operations

- [ ] Check new orders
- [ ] Update processing orders
- [ ] Mark shipped orders
- [ ] Process returns/cancellations
- [ ] Respond to customer inquiries

### Weekly Maintenance

- [ ] Review order trends
- [ ] Check payment methods
- [ ] Analyze customer feedback
- [ ] Monitor system performance

### Monthly Review

- [ ] Audit order history
- [ ] Check system logs
- [ ] Review user feedback
- [ ] Plan improvements

---

## 🚀 Keyboard Shortcuts

```
Ctrl+K     → Focus search field
Tab        → Move to next form field
Enter      → Submit form
Esc        → Close modal
```

---

## 📝 Frequently Asked Questions

**Q: How do I export orders?**
A: Click "Export CSV" button (top right of order list)

**Q: Can I batch update orders?**
A: Currently, single orders only. Planned for future.

**Q: How long is order history kept?**
A: All history is kept indefinitely.

**Q: Can I undo a cancelled order?**
A: No, cancellation is irreversible by design (safety feature).

**Q: What information is logged?**
A: Status changes, payment updates, and tracking changes are logged.

**Q: Can I customize order statuses?**
A: Not currently, but can be added as enhancement.

**Q: How are permissions enforced?**
A: Via role-based middleware (role:admin).

**Q: Is there audit logging?**
A: Yes, all changes are logged in order_histories table.

---

## 🎓 Learning Resources

### For Frontend Developers

- Bootstrap 5: https://getbootstrap.com/docs/5.0/
- Blade Templating: https://laravel.com/docs/blade
- HTML Forms: https://developer.mozilla.org/en-US/docs/Learn/Forms

### For Backend Developers

- Laravel Controllers: https://laravel.com/docs/controllers
- Eloquent ORM: https://laravel.com/docs/eloquent
- Query Builder: https://laravel.com/docs/queries

### For Designers

- Design System: See ORDER_MANAGEMENT_VISUAL_GUIDE.md
- Color Palette: Defined in admin-custom.css
- Typography: Nunito font family

---

## ✨ Tips & Tricks

### Faster Navigation

1. Use search for single orders
2. Use status tabs for groups
3. Use date range for periods
4. Use payment method for specific types

### Better Organization

1. Add descriptive admin notes
2. Use consistent status updates
3. Log all important changes
4. Review history regularly

### Improved Efficiency

1. Combine filters for precision
2. Use keyboard shortcuts
3. Batch similar updates
4. Check order before actions

---

## 🔔 Important Reminders

⚠️ **Critical Points**

- ✅ All changes are logged for audit
- ✅ Cancellations are permanent
- ✅ Emails sent to customers on updates
- ✅ Date filters use server timezone
- ✅ Always verify before actions

📌 **Best Practices**

- Always add admin notes
- Review information before updating
- Verify customer contact details
- Check payment status
- Monitor for errors

---

## 📞 Support Channels

For issues or questions:

1. Check this quick start guide
2. Review documentation files
3. Contact system administrator
4. Check application logs
5. Review error messages

---

**Ready to use!** 🚀

For detailed information, see the complete documentation in `/Documentation/` folder.
