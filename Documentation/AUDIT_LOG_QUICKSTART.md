# Audit Log Feature - Quick Start Guide

## Installation (Already Completed)

The system-wide audit log feature has been installed. Here's what was set up:

### ✅ Files Created

1. **Migration** - `database/migrations/2026_01_24_000000_create_audit_logs_table.php`
2. **Model** - `app/Models/AuditLog.php`
3. **Trait** - `app/Traits/Auditable.php`
4. **Controller** - `app/Http/Controllers/AuditLogController.php`
5. **Views** - `resources/views/admin/audit-logs/`
    - `index.blade.php` (listing & filtering)
    - `show.blade.php` (detailed view)
    - `model-history.blade.php` (timeline view)
6. **Routes** - Added to `routes/web.php`

### ✅ Models Updated

- `app/Models/Product.php` - Added `Auditable` trait
- `app/Models/Order.php` - Added `Auditable` trait
- `app/Models/User.php` - Added `Auditable` trait

### ✅ Database

- Migration run successfully
- `audit_logs` table created with proper indexes

## Access the Audit Log Interface

**URL:** `http://yourapp.com/admin/audit-logs`

**Requirements:**

- Must be logged in as an admin user
- Admin role required (role_id = 1)

## Features Available

### 1. View All Logs

Visit `/admin/audit-logs` to see all system changes with:

- User information
- Action type (Created/Updated/Deleted)
- Model type and ID
- IP address and timestamp
- One-click access to details

### 2. Filter Logs

Use the filter panel to narrow results by:

- **Model Type** - Product, Order, or User
- **Action** - Created, Updated, or Deleted
- **User** - Who made the change
- **Date Range** - Start and end dates
- **Model ID** - Specific record ID

### 3. View Detailed Changes

Click the eye icon to see:

- User who made the change
- IP address and browser info
- Before/after values (for updates)
- Initial values (for creations)
- Deleted values (for deletions)

### 4. View Model History

Click the history icon to see a complete timeline of all changes to a specific model:

- Visual timeline layout
- Color-coded by action type
- Shows each field that changed
- User and timestamp for each change

### 5. Export Data

Download filtered logs as CSV for analysis in Excel/Sheets:

- Click "Export CSV" button
- Select desired filters first
- All matching logs exported

## Example Scenarios

### Scenario 1: Track Product Price Changes

```
1. Go to /admin/audit-logs
2. Filter:
   - Model Type: Product
   - Action: Updated
3. See who changed prices, when, and old vs new values
```

### Scenario 2: Audit User Account Changes

```
1. Go to /admin/audit-logs
2. Filter:
   - Model Type: User
   - User: [Select specific user]
3. See all changes made by or to that user
```

### Scenario 3: Investigate Order Modifications

```
1. Go to /admin/audit-logs
2. Filter:
   - Model Type: Order
   - Model ID: 123 (the order number)
3. See complete history of who modified this order and what changed
```

### Scenario 4: Monthly Report

```
1. Go to /admin/audit-logs
2. Filter:
   - Start Date: 2026-01-01
   - End Date: 2026-01-31
3. Click "Export CSV"
4. Open in Excel for reporting
```

## Adding Auditing to New Models

To audit changes to a new model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class YourModel extends Model
{
    use Auditable;

    // Optional: Customize which fields are audited
    public function getAuditableAttributes(): array
    {
        return ['name', 'email', 'status']; // Only these fields
    }
}
```

That's it! Your model changes will automatically be logged.

## Viewing Logs Programmatically

```php
<?php

use App\Models\AuditLog;

// Get latest changes to a product
$logs = AuditLog::where('model_type', 'App\Models\Product')
    ->where('model_id', 5)
    ->latest()
    ->get();

foreach ($logs as $log) {
    echo $log->user->name . " ";
    echo $log->action . " ";
    echo class_basename($log->model_type) . " ";
    echo "on " . $log->created_at->format('Y-m-d H:i:s');
    echo " from " . $log->ip_address;
}

// Get all changes by a specific user
$userActivity = AuditLog::where('user_id', auth()->id())
    ->latest()
    ->paginate(50);

// Export summary statistics
$stats = [
    'total_logs' => AuditLog::count(),
    'by_action' => AuditLog::groupBy('action')->count(),
    'by_model' => AuditLog::groupBy('model_type')->count(),
];
```

## Database Queries

### Find who deleted a product

```sql
SELECT * FROM audit_logs
WHERE model_type = 'App\Models\Product'
  AND model_id = 5
  AND action = 'deleted';
```

### Get all changes in last 7 days

```sql
SELECT * FROM audit_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY created_at DESC;
```

### Find suspicious activity

```sql
SELECT user_id, COUNT(*) as changes
FROM audit_logs
WHERE action = 'deleted'
  AND DATE(created_at) = CURDATE()
GROUP BY user_id
HAVING changes > 10;
```

## Performance Tips

1. **Regular Cleanup** - Archive logs older than 1 year

    ```sql
    DELETE FROM audit_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
    ```

2. **Monitor Table Size**

    ```sql
    SELECT
        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
    FROM information_schema.TABLES
    WHERE table_name = 'audit_logs';
    ```

3. **Use Pagination** - Always paginate in views
    - Default: 25 per page (index)
    - Model history: 50 per page

## Troubleshooting

### "Audit logs not being created"

- [ ] Check model has `use Auditable` trait
- [ ] Verify changes are from authenticated user
- [ ] Check `Auth::id()` is not null

### "Slow loading audit logs"

- [ ] Check indexes exist:
    ```sql
    SHOW INDEX FROM audit_logs;
    ```
- [ ] Paginate results
- [ ] Use filters to narrow dataset

### "Very large JSON values"

- Use `getAuditableAttributes()` to limit audited fields
- Or implement log rotation policy

## Admin Menu Integration

Add to your admin navigation menu:

```html
<li>
    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link">
        <i class="fas fa-list"></i>
        <span>Audit Logs</span>
    </a>
</li>
```

## API Endpoints

Get statistics for dashboard widgets:

```bash
GET /admin/audit-logs/statistics

# Response
{
    "totalLogs": 15234,
    "logsByAction": {
        "created": 5234,
        "updated": 8901,
        "deleted": 1099
    },
    "logsByModel": [
        { "model_type": "App\Models\Product", "total": 8123 },
        { "model_type": "App\Models\Order", "total": 5234 },
        { "model_type": "App\Models\User", "total": 1877 }
    ],
    "recentLogs": [...]
}
```

## Next Steps

1. ✅ Test the audit log feature in your admin panel
2. ✅ Add filters to monitor critical changes
3. ✅ Integrate with your admin menu
4. ✅ Train team on using audit logs for investigations
5. ✅ Set up log archival/rotation policy
6. Consider adding email alerts for sensitive operations

## Support & Documentation

- Full feature documentation: `/doc/AUDIT_LOG_FEATURE.md`
- Laravel Observers: https://laravel.com/docs/eloquent#observers
- Artisan commands: `php artisan make:observer`

---

**Status:** ✅ Ready to use
**Date:** January 24, 2026
