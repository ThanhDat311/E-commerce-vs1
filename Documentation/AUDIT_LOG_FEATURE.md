# System-wide Audit Log Feature Documentation

## Overview

The Audit Log feature provides comprehensive tracking of all changes made to critical models in the system (Product, Order, User). It automatically records who made changes, what changed, when it happened, and from which IP address.

## Architecture

### Components

#### 1. **AuditLog Model** (`app/Models/AuditLog.php`)

- Stores audit log entries with the following fields:
    - `id` - Primary key
    - `user_id` - User who performed the action (nullable for system actions)
    - `action` - Type of action: 'created', 'updated', 'deleted'
    - `model_type` - Full class name of audited model (e.g., `App\Models\Product`)
    - `model_id` - ID of the audited model
    - `old_values` - JSON representation of values before change
    - `new_values` - JSON representation of values after change
    - `ip_address` - IP address of the user who made the change
    - `user_agent` - Browser/client information
    - `created_at`, `updated_at` - Timestamps

**Key Methods:**

```php
// Accessor attributes
$log->old_values  // Returns decoded JSON array
$log->new_values  // Returns decoded JSON array

// Relationships
$log->user()      // Belongs to User model

// Query scopes
AuditLog::byModelType($type)
AuditLog::byAction($action)
AuditLog::byUser($userId)
AuditLog::byModelId($modelId)
AuditLog::byDateRange($startDate, $endDate)
```

#### 2. **Auditable Trait** (`app/Traits/Auditable.php`)

Automatically tracks changes to models by listening to Eloquent events.

**Features:**

- Records `created` events when a model is created
- Records `updated` events when a model is modified
- Records `deleted` events when a model is deleted
- Captures old and new values for comparisons
- Only logs actual changes (ignores no-op updates)
- Excludes system fields (timestamps, tokens)

**Usage:**

```php
namespace App\Models;

use App\Traits\Auditable;

class Product extends Model
{
    use Auditable;

    // Optional: customize auditable attributes
    public function getAuditableAttributes(): array
    {
        return ['name', 'price', 'stock_quantity']; // Only audit these fields
    }
}
```

**Bootstrap Method:**
The trait hooks into Eloquent's boot process using:

```php
public static function bootAuditable(): void
{
    static::created(function ($model) {
        static::createAuditLog($model, 'created');
    });

    static::updated(function ($model) {
        static::createAuditLog($model, 'updated');
    });

    static::deleted(function ($model) {
        static::createAuditLog($model, 'deleted');
    });
}
```

#### 3. **AuditLogController** (`app/Http/Controllers/AuditLogController.php`)

**Methods:**

- **`index()`** - List all audit logs with filtering
    - Filters: model_type, action, user_id, start_date, end_date, model_id
    - Returns paginated results (25 per page)

- **`show(AuditLog $auditLog)`** - Display detailed information about a log
    - Shows user info, network details, and changes
    - Attempts to load the related model if still exists

- **`modelHistory(Request $request)`** - View complete change history of a specific model
    - Parameters: `model_type`, `model_id`
    - Returns paginated timeline view (50 per page)

- **`statistics()`** - Get audit log statistics
    - Returns JSON with counts by action and model type
    - Useful for dashboards

- **`export(Request $request)`** - Export filtered logs as CSV
    - Supports same filters as index
    - Returns downloadable CSV file

#### 4. **Views**

**`admin/audit-logs/index.blade.php`** - Main audit log listing

- Advanced filtering interface
- Table display with action badges
- Quick links to model history and details
- Export functionality

**`admin/audit-logs/show.blade.php`** - Detailed log view

- User and network information
- Side-by-side comparison of old vs new values
- Links to related records
- Model history timeline

**`admin/audit-logs/model-history.blade.php`** - Model change timeline

- Visual timeline of all changes to a specific model
- Shows before/after values for each change
- Color-coded by action type
- Responsive design for mobile

## Database Schema

```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NULLABLE (FOREIGN KEY -> users),
    action VARCHAR(255),                          -- 'created', 'updated', 'deleted'
    model_type VARCHAR(255),                      -- e.g., 'App\Models\Product'
    model_id BIGINT,                              -- ID of the audited model
    old_values LONGTEXT NULLABLE (JSON),          -- Previous values
    new_values LONGTEXT NULLABLE (JSON),          -- New values
    ip_address VARCHAR(45) NULLABLE,              -- IPv4/IPv6
    user_agent TEXT NULLABLE,                     -- Browser info
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- Indexes for optimal query performance
    INDEX idx_model_type_id (model_type, model_id),
    INDEX idx_user_created (user_id, created_at),
    INDEX idx_action (action)
);
```

## Implementation Details

### Models Using Auditable Trait

1. **Product** (`app/Models/Product.php`)
    - Tracks: category_id, name, sku, price, sale_price, stock_quantity, etc.

2. **Order** (`app/Models/Order.php`)
    - Tracks: user_id, status, payment status, total, address info, etc.

3. **User** (`app/Models/User.php`)
    - Tracks: name, email, role_id, phone_number, address, is_active, etc.

### Routes

All routes are prefixed with `/admin/audit-logs` and require authentication with admin role:

```
GET    /admin/audit-logs                          -> admin.audit-logs.index
GET    /admin/audit-logs/{auditLog}               -> admin.audit-logs.show
GET    /admin/audit-logs/model/history            -> admin.audit-logs.model-history
GET    /admin/audit-logs/statistics               -> admin.audit-logs.statistics
GET    /admin/audit-logs/export                   -> admin.audit-logs.export
```

## Usage Examples

### View All Audit Logs

```php
Route::get('/admin/audit-logs', [AuditLogController::class, 'index']);
```

Access at: `/admin/audit-logs`

**Query Parameters:**

- `model_type` - Filter by model class
- `action` - Filter by action (created, updated, deleted)
- `user_id` - Filter by user
- `start_date` - Filter from date
- `end_date` - Filter to date
- `model_id` - Filter by model ID

### View Model History

```php
// View change history of Product #5
GET /admin/audit-logs/model/history?model_type=App\Models\Product&model_id=5
```

### Export Logs

```php
// Export filtered logs as CSV
GET /admin/audit-logs/export?model_type=App\Models\Product&action=updated
```

### Query Logs Programmatically

```php
// Get all updates to Product #5
$logs = AuditLog::byModelType('App\Models\Product')
    ->byModelId(5)
    ->byAction('updated')
    ->latest('created_at')
    ->get();

// Get logs within date range
$logs = AuditLog::byDateRange('2026-01-01', '2026-01-31')
    ->byUser(auth()->id())
    ->get();

// Check who deleted a product
$deletionLog = AuditLog::where('model_type', 'App\Models\Product')
    ->where('model_id', 5)
    ->where('action', 'deleted')
    ->first();

if ($deletionLog) {
    echo "Deleted by: " . $deletionLog->user->name;
    echo "On: " . $deletionLog->created_at;
    echo "From IP: " . $deletionLog->ip_address;
}
```

### Add Auditable to New Models

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Review extends Model
{
    use Auditable;

    // Optional: Only audit specific fields
    public function getAuditableAttributes(): array
    {
        return ['product_id', 'user_id', 'rating', 'comment'];
    }
}
```

## Features

### ✅ Automatic Change Tracking

- No manual code needed
- Changes logged automatically via observers
- Captures before/after values

### ✅ Comprehensive Filtering

- By model type
- By action (create/update/delete)
- By user
- By date range
- By model ID

### ✅ Detailed Change History

- Visual timeline view
- Side-by-side value comparison
- User and IP information
- Browser/user agent tracking

### ✅ Data Export

- Export filtered logs to CSV
- Preserves all relevant information
- Easy integration with external tools

### ✅ Statistics Dashboard

- Count by action type
- Count by model type
- Recent activity tracking

### ✅ Performance Optimized

- Composite indexes for queries
- Efficient JSON storage
- Pagination for large datasets

## Security Considerations

1. **Access Control**: Audit logs accessible only to admin users
2. **Data Integrity**: JSON values stored as-is for audit trail accuracy
3. **User Attribution**: All changes linked to authenticated user
4. **IP Tracking**: Captures client IP for forensic investigation
5. **Deleted Records**: History preserved even after model deletion

## Performance Impact

- **Minimal overhead**: Logging happens after model save
- **Async option**: Can be queued for high-traffic scenarios
- **Database**: Uses indexes for efficient queries
- **Disk space**: ~500 bytes per log entry average

## Customization

### Exclude Fields from Auditing

```php
public function getAuditableAttributes(): array
{
    return collect($this->getAttributes())
        ->except(['created_at', 'updated_at', 'password', 'api_token'])
        ->keys()
        ->toArray();
}
```

### Custom Audit Log Creation

```php
// Override in your model
protected static function createAuditLog($model, string $action)
{
    // Custom logic here
    parent::createAuditLog($model, $action);
}
```

### Queue Audit Logs (Future Enhancement)

```php
// In AuditLog model
protected static function createAuditLog($model, string $action)
{
    dispatch(new ProcessAuditLog($model, $action));
}
```

## Troubleshooting

### Logs not being created

1. Verify model has `use Auditable` trait
2. Check middleware doesn't prevent logging
3. Ensure `Auth::id()` returns valid user

### High disk usage

1. Archive old logs: `DELETE FROM audit_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)`
2. Implement log rotation policy

### Slow queries

1. Check indexes exist on audit_logs table
2. Add additional indexes if filtering specific columns frequently
3. Partition large tables by date

## Future Enhancements

- [ ] Audit log restoration (undo changes)
- [ ] Real-time activity dashboard
- [ ] Email notifications for critical changes
- [ ] Two-factor authentication for sensitive operations
- [ ] Encrypted audit log storage
- [ ] Webhooks for external system integration
- [ ] Machine learning for anomaly detection

## Support

For issues or questions, refer to:

- [Laravel Observers Documentation](https://laravel.com/docs/eloquent#observers)
- [Laravel Query Builder](https://laravel.com/docs/queries)
- Application documentation: `/doc`

---

**Created:** January 24, 2026
**Last Updated:** January 24, 2026
**Version:** 1.0.0
