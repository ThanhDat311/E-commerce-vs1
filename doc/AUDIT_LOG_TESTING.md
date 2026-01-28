# Audit Log Feature - Testing Guide

## Pre-Test Checklist

- [ ] Migration has run successfully
- [ ] All files are created
- [ ] Models have Auditable trait
- [ ] Routes are configured
- [ ] Admin user exists with role_id = 1

## Testing the Implementation

### Test 1: Verify Database Table

```bash
# SSH into database
mysql -u root -p your_database

# Check table exists
SHOW TABLES LIKE 'audit_logs';

# Check structure
DESCRIBE audit_logs;

# Expected output:
# id, user_id, action, model_type, model_id, old_values, new_values,
# ip_address, user_agent, created_at, updated_at
```

### Test 2: Create Test Data

Run the demo seeder to generate test data:

```bash
php artisan db:seed --class=AuditLogDemoSeeder
```

**Expected Output:**

```
=== Audit Log Demo Seeder ===
✓ Admin user ready: Admin User

Demo 1: Creating a product...
✓ Product created: Demo Product (ID: X)

Demo 2: Updating product price...
✓ Product updated

Demo 3: Creating an order...
✓ Order created: Order #X

Demo 4: Updating order status...
✓ Order updated

Demo 5: Updating user profile...
✓ User updated

=== Audit Logs Created ===
ID: 1 | Action: created | User: Admin User | Model: Product #X
ID: 2 | Action: updated | User: Admin User | Model: Product #X
...

=== Demo Complete ===
✓ Visit /admin/audit-logs to see the audit log interface
✓ 5 audit logs created
```

### Test 3: Verify Audit Log Creation

```bash
php artisan tinker

# Check audit logs were created
App\Models\AuditLog::count()
# Should return: 5 or more

# View recent logs
App\Models\AuditLog::latest()->limit(3)->get();

# Check specific model
App\Models\AuditLog::where('model_type', 'App\Models\Product')->get();
```

### Test 4: Test Admin Interface

1. **Login to Admin Panel**
    - URL: `/admin`
    - Email: admin@example.com (or your admin user)
    - Password: your admin password

2. **Navigate to Audit Logs**
    - URL: `/admin/audit-logs`
    - Should see main listing page

3. **Test Filters**
    - [ ] Filter by Model Type (select "Product")
    - [ ] Filter by Action (select "Created")
    - [ ] Filter by User (select admin user)
    - [ ] Set Date Range
    - [ ] Enter Model ID
    - [ ] Click Filter button
    - [ ] Click Reset button

4. **Test Detail View**
    - Click eye icon on any log
    - Verify all information displays correctly:
        - [ ] User name and email
        - [ ] Action type
        - [ ] Model type and ID
        - [ ] IP address and user agent
        - [ ] Created date and time

5. **Test Model History**
    - Click history icon on any log
    - Verify timeline displays:
        - [ ] All changes to that model
        - [ ] Color coding by action type
        - [ ] Before/after values for updates
        - [ ] Proper chronological order

6. **Test Export**
    - Apply some filters
    - Click "Export CSV"
    - Save the file
    - Open in Excel/Sheets
    - Verify all columns are present:
        - [ ] ID
        - [ ] User
        - [ ] Action
        - [ ] Model Type
        - [ ] Model ID
        - [ ] IP Address
        - [ ] User Agent
        - [ ] Created At
        - [ ] Old Values
        - [ ] New Values

### Test 5: Create Live Audit Log

Create a new product while logged in as admin:

```bash
# In admin panel or via tinker
$product = App\Models\Product::create([
    'category_id' => 1,
    'name' => 'Test Product ' . time(),
    'sku' => 'TEST-' . time(),
    'price' => 99.99,
    'stock_quantity' => 50,
    'description' => 'Test product',
]);
```

Then:

1. Go to `/admin/audit-logs`
2. Should see new "created" entry at top
3. Click to view details
4. Verify all initial values are logged

### Test 6: Update Model

Update a product in admin panel or via command:

```php
// In tinker
$product = App\Models\Product::find(1);
$product->update(['price' => 199.99, 'stock_quantity' => 25]);
```

Then:

1. Go to `/admin/audit-logs`
2. Filter by Product model
3. Should see new "updated" entry
4. Click to view details
5. Verify "Before" and "After" values show the change

### Test 7: Delete Model

```php
// In tinker
App\Models\Product::find(1)->delete();
```

Then:

1. Go to `/admin/audit-logs`
2. Filter by action "deleted"
3. Should see "deleted" entry
4. Verify old values are preserved

### Test 8: Query Scopes

Test the query scopes in tinker:

```php
use App\Models\AuditLog;

// By model type
AuditLog::byModelType('App\Models\Product')->count();

// By action
AuditLog::byAction('updated')->count();

// By user
AuditLog::byUser(1)->count();

// By model ID
AuditLog::byModelId(5)->count();

// By date range
AuditLog::byDateRange('2026-01-01', '2026-01-31')->count();

// Chained queries
AuditLog::byModelType('App\Models\Order')
    ->byAction('created')
    ->byUser(1)
    ->latest()
    ->get();
```

### Test 9: Pagination

```bash
# Check pagination works
GET /admin/audit-logs?page=1
GET /admin/audit-logs?page=2

# Should show different results, default 25 per page
```

### Test 10: API Endpoint

Test statistics endpoint:

```bash
curl -X GET http://yourapp.com/admin/audit-logs/statistics \
  -H "Authorization: Bearer YOUR_TOKEN"

# Expected JSON response:
{
    "totalLogs": 15,
    "logsByAction": {
        "created": 5,
        "updated": 7,
        "deleted": 3
    },
    "logsByModel": [...],
    "recentLogs": [...]
}
```

### Test 11: IP Address Tracking

1. Create/update a model while logged in
2. View the audit log
3. Verify IP address shows in log
4. Check it matches your current IP:
    - Visit http://yourapp.com/test-csrf
    - Should show your IP in response

### Test 12: User Agent Tracking

1. Create/update a model
2. View audit log details
3. Check user_agent field contains browser info
4. Example: "Mozilla/5.0 (Windows NT 10.0; Win64; x64)..."

## Stress Testing

### Test Large Dataset

Create many logs to test performance:

```php
// In tinker - creates 1000 logs
for ($i = 0; $i < 200; $i++) {
    $product = App\Models\Product::create([
        'category_id' => 1,
        'name' => 'Product ' . $i,
        'sku' => 'SKU-' . $i,
        'price' => rand(10, 100),
        'stock_quantity' => rand(1, 100),
    ]);
}

// Check performance
AuditLog::count(); // Should be 200+ logs
```

Then:

1. Visit `/admin/audit-logs`
2. Should load within 1-2 seconds
3. Test pagination with large dataset
4. Verify filters still work efficiently

## Security Testing

### Test 1: Access Control

```bash
# As unauthenticated user
GET /admin/audit-logs
# Should redirect to login

# As non-admin user (role_id != 1)
# Should receive 403 Forbidden
```

### Test 2: Data Sensitivity

1. View audit logs
2. Check that:
    - [ ] Passwords are NOT logged
    - [ ] API tokens are NOT logged
    - [ ] Sensitive fields are handled properly
    - [ ] Only specified fields are recorded

### Test 3: Injection Protection

Test that special characters are handled safely:

```php
$product = App\Models\Product::create([
    'name' => 'Test <script>alert("xss")</script>',
    'description' => "Test'; DROP TABLE audit_logs; --",
]);

// View audit log in admin panel
// Verify special characters are escaped
```

## Cleanup

After testing, optionally clear test data:

```bash
# Delete all audit logs
php artisan tinker
App\Models\AuditLog::truncate();

# Or delete specific model logs
App\Models\AuditLog::where('model_type', 'App\Models\Product')->delete();
```

## Bug Reporting Checklist

If you encounter issues:

- [ ] Check error in logs: `storage/logs/laravel.log`
- [ ] Verify database table exists: `DESCRIBE audit_logs`
- [ ] Check Auditable trait is applied: model uses trait
- [ ] Verify routes exist: `php artisan route:list | grep audit`
- [ ] Check Auth::id() returns value: verify user is authenticated
- [ ] Test in tinker first before admin panel

## Performance Benchmarks

Expected performance metrics:

| Operation        | Expected Time |
| ---------------- | ------------- |
| Create audit log | < 5ms         |
| List 25 logs     | < 100ms       |
| Filter logs      | < 200ms       |
| Export 1000 logs | < 500ms       |
| Query statistics | < 150ms       |

## Success Criteria

All tests pass when:

- ✅ Migration created table successfully
- ✅ Audit logs created automatically on model changes
- ✅ Admin panel loads and displays logs
- ✅ Filters work correctly
- ✅ Detail view shows all information
- ✅ Model history timeline displays changes
- ✅ CSV export works
- ✅ IP and user agent are captured
- ✅ Performance is acceptable
- ✅ Security measures are in place

---

**Test Date:** ******\_\_\_******
**Tester:** ******\_\_\_******
**Status:** ☐ Pass ☐ Fail
**Notes:** ******\_\_\_******
