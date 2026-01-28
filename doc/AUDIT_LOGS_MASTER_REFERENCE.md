# Audit Logs - Master Reference Document

## 📚 Complete System Reference

Authoritative reference for all aspects of the Audit Logs feature.

---

## 🎯 System Overview

### What Is Audit Logs?

A compliance-focused, security-oriented audit trail system that tracks all changes made in the system with immutable records of who made each change, when it happened, what changed, and where it came from (IP address).

### Core Purpose

- **Compliance**: Prove what happened and when
- **Security**: Detect unauthorized access
- **Accountability**: Know who did what
- **Investigation**: Reconstruct events
- **Forensics**: Analyze changes
- **Audit Trail**: Historical record

### Design Philosophy

- **Minimal**: Only necessary information
- **Serious**: Professional, business-focused
- **Immutable**: Can't be altered or deleted
- **Comprehensive**: Nothing hidden
- **Secure**: Read-only by design

---

## 🗂️ System Architecture

### Backend Components

**1. Model: `AuditLog`**

```php
class AuditLog extends Model
{
    // Relationships
    belongsTo(User)           // Who made the change

    // Attributes
    user_id                   // User who made change
    action                    // created|updated|deleted
    model_type                // Class name (e.g., Product)
    model_id                  // Record ID
    old_values                // JSON of previous values
    new_values                // JSON of new values
    ip_address                // Source IP
    user_agent                // Browser info
    created_at                // When it happened
    updated_at                // System timestamp
}
```

**2. Controller: `AuditLogController`**

```
index()             → List logs with filters
show()              → View details
modelHistory()      → Timeline for resource
statistics()        → Analytics/metrics
export()            → Download as CSV
```

**3. Database Table: `audit_logs`**

```sql
Columns:
  id              BIGINT PRIMARY KEY
  user_id         BIGINT (foreign key to users)
  action          VARCHAR (created|updated|deleted)
  model_type      VARCHAR (class name)
  model_id        BIGINT (record ID)
  old_values      LONGTEXT (JSON)
  new_values      LONGTEXT (JSON)
  ip_address      VARCHAR (IPv4 or IPv6)
  user_agent      TEXT (browser info)
  created_at      TIMESTAMP
  updated_at      TIMESTAMP

Indexes:
  (model_type, model_id)    → Fast resource queries
  (user_id, created_at)     → Fast user queries
  (action)                  → Fast action queries
```

### Frontend Components

**1. View: `audit-logs/index.blade.php`**

- Minimal table display
- Advanced filter panel
- Color-coded badges
- Professional styling

**2. Routes**

```
GET /admin/audit-logs              → index (list)
GET /admin/audit-logs/{id}         → show (details)
GET /admin/audit-logs/model/history → modelHistory (timeline)
GET /admin/audit-logs/statistics   → statistics (metrics)
GET /admin/audit-logs/export       → export (CSV)
```

---

## 🎨 Visual Specifications

### Colors

**Primary Palette**

- Slate-900: `#0f172a` - Headers, serious elements
- Slate-700: `#374151` - Secondary text
- Gray-600: `#4b5563` - Tertiary text
- Gray-200: `#e5e7eb` - Borders

**Action Colors**

- Green: `#16a34a` - Created (green-700)
- Blue: `#2563eb` - Updated (blue-700)
- Red: `#dc2626` - Deleted (red-700)

**Badge Backgrounds**

- Green-50: `#dcfce7` - Created badge
- Blue-50: `#dbeafe` - Updated badge + User badge
- Red-50: `#fee2e2` - Deleted badge

### Typography

**Font Stack**

```css
Primary: system-ui, -apple-system, sans-serif
Monospace: ui-monospace, "Courier New", monospace
```

**Type Sizes**

- Page Title: 3xl (30px), bold
- Section: lg (18px), bold
- Label: xs (12px), uppercase, bold
- Body: sm/base (13-14px), normal
- Timestamp: xs (12px), monospace
- IP Address: xs (12px), monospace

### Layout

**Page Sections**

```
Header
  ├─ Shield icon + title
  ├─ Subtitle
  └─ Export CSV button

Filters
  ├─ Row 1: Start Date, End Date, User, Action
  ├─ Row 2: Resource Type, Resource ID, Buttons
  └─ Apply / Reset buttons

Table
  ├─ Entry count display
  ├─ Column headers: Timestamp, User, Action, Resource, IP, Details
  ├─ Data rows with data
  └─ Pagination footer

Empty State
  ├─ Icon
  ├─ Message
  └─ Reset button
```

---

## 📊 Database Schema

### audit_logs Table

```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULLABLE,
    action VARCHAR(255) NOT NULL,  -- created, updated, deleted
    model_type VARCHAR(255) NOT NULL,  -- Class name
    model_id BIGINT UNSIGNED NOT NULL,  -- Record ID
    old_values LONGTEXT NULLABLE,  -- JSON format
    new_values LONGTEXT NULLABLE,  -- JSON format
    ip_address VARCHAR(45) NULLABLE,  -- IPv4 or IPv6
    user_agent TEXT NULLABLE,  -- Browser string
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_model (model_type, model_id),
    INDEX idx_user (user_id, created_at),
    INDEX idx_action (action)
);
```

### Sample Data

```sql
INSERT INTO audit_logs (user_id, action, model_type, model_id, old_values, new_values, ip_address, user_agent)
VALUES (
    1,
    'updated',
    'App\Models\Product',
    42,
    '{"price":"99.99","stock":"50"}',
    '{"price":"129.99","stock":"45"}',
    '192.168.1.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
);
```

---

## 🔐 Security Features

### Immutability

**What Can't Change**

- ❌ Audit log entries can't be edited
- ❌ Audit log entries can't be deleted
- ❌ Timestamps can't be modified
- ❌ User attribution can't be changed

**Why This Matters**
If audit logs could be modified, the entire audit trail would be worthless. Immutability is the foundation of security.

### Access Control

**Who Can Access**

- Administrators only
- Requires admin authorization
- Middleware protected

**What They Can Do**

- ✅ View audit logs
- ✅ Filter logs
- ✅ Export logs
- ✅ View details
- ✅ View history

**What They Can't Do**

- ❌ Edit log entries
- ❌ Delete log entries
- ❌ Modify timestamps
- ❌ Hide entries

### Data Security

**Sensitive Information**

- IP addresses stored for security analysis
- User agent stored for forensics
- Before/after values for change tracking
- All stored as-is without redaction

**Privacy Considerations**

- Admin only access
- Immutable records
- Proper retention policy
- Secure export format

---

## 📋 Filtering System

### Filter Types

**1. Date Range**

```
Field: Start Date
Type: Date input
Format: YYYY-MM-DD
Operator: Greater than or equal
Logic: Inclusive start

Field: End Date
Type: Date input
Format: YYYY-MM-DD
Operator: Less than or equal
Logic: Inclusive end
```

**2. User Filter**

```
Field: User
Type: Dropdown select
Options: List of all users
Operator: Exact match
Default: All users (empty)
```

**3. Action Filter**

```
Field: Action
Type: Dropdown select
Options: Created, Updated, Deleted
Operator: Exact match
Default: All actions (empty)
```

**4. Resource Type**

```
Field: Resource Type
Type: Text input
Format: Model class name (e.g., "Product")
Operator: Exact match or contains
Default: All types (empty)
```

**5. Resource ID**

```
Field: Resource ID
Type: Number input
Format: Integer
Operator: Exact match
Default: Any ID (empty)
```

### Filter Combinations

**Valid Combinations**

- ✅ Date only
- ✅ User only
- ✅ Date + User
- ✅ All filters together
- ✅ Any combination

**Examples**

```
Example 1: Find John's creations
  User: John Doe
  Action: Created

Example 2: Yesterday's product changes
  Start Date: [Yesterday]
  End Date: [Yesterday]
  Resource Type: Product

Example 3: Specific resource history
  Resource Type: Order
  Resource ID: 1289
```

---

## 🎯 Action Types

### Created

- **What**: New record created
- **Color**: Green (#16a34a)
- **Icon**: fa-plus-circle
- **Badge**: [✓ Created]
- **Example**: "Product #42 created"

### Updated

- **What**: Existing record modified
- **Color**: Blue (#2563eb)
- **Icon**: fa-edit
- **Badge**: [✎ Updated]
- **Example**: "Product #42 price changed"

### Deleted

- **What**: Record removed
- **Color**: Red (#dc2626)
- **Icon**: fa-trash
- **Badge**: [🗑 Deleted]
- **Example**: "Product #42 deleted"

---

## 👥 User Attribution

### Registered Users

```
Display: [👤 Name] in blue badge
Style: Blue-50 background, Blue-700 text
Icon: User icon (fa-user)
Action: Can click to view user profile
Meaning: Active user who made the change
```

### Unknown/Deleted Users

```
Display: [❌ Unknown User] in gray badge
Style: Gray-100 background, Gray-600 text
Icon: None
Action: No profile available
Meaning: User account no longer exists
```

### System Actions

```
Display: [⚙️ System] in gray badge
Style: Gray-100 background, Gray-600 text
Icon: System icon
Action: No user profile
Meaning: Automated system action
```

---

## ⏰ Timestamp Format

### Display Format

```
Date: Mon DD, YYYY     (e.g., Jan 29, 2026)
Time: HH:MM:SS         (e.g., 14:30:45)
Timezone: Server timezone
Precision: To the second
Font: Monospace (font-mono)
```

### Examples

```
Jan 29, 2026
14:30:45

Feb 01, 2026
09:15:23

Dec 31, 2025
23:59:59
```

### Sorting

- Default: Newest first (descending)
- Can be changed: Click header
- Pagination: 25 per page default

---

## 🌐 IP Address Tracking

### Format

```
Display: Monospace text (font-mono)
Style: Gray-600 text, subtle gray background
Icon: Network icon (fa-network-wired)
Length: Up to 45 characters (IPv6 support)
Fallback: "—" (dash) if not available
```

### Examples

```
IPv4: 192.168.1.1
IPv4: 203.45.67.89
IPv6: 2001:0db8:85a3:0000:0000:8a2e:0370:7334
IPv6: ::1 (localhost)
Missing: —
```

### Security Analysis

```
Pattern Recognition:
  Normal IP: 192.168.1.100  ← Office network
  Unusual IP: 203.45.67.89  ← Overseas
  → Investigate unusual access

Access Control:
  Admin IP: 192.168.1.10    ← Expected
  Regular user IP: 10.20.30.40 ← Suspicious privilege
  → Verify authorization

Anomaly Detection:
  User A: Always 192.168.1.5
  User A: Now from 8.8.8.8 (different location)
  → Possible account compromise
```

---

## 📤 Export Function

### CSV Format

```
Columns: timestamp, user, action, model_type, model_id, ip_address, user_agent
Delimiter: Comma
Encoding: UTF-8
Line endings: CRLF
Quote character: Double quote (")
```

### Example CSV Output

```csv
timestamp,user,action,model_type,model_id,ip_address,user_agent
2026-01-29 14:30:45,John Doe,created,Product,42,192.168.1.1,Mozilla/5.0
2026-01-29 14:35:12,John Doe,updated,Product,42,192.168.1.1,Mozilla/5.0
2026-01-29 14:40:33,Jane Smith,updated,Product,42,192.168.1.5,Firefox/122
2026-01-29 14:45:00,Admin,deleted,Product,42,10.0.0.1,Safari/17
```

### Use Cases

```
Monthly Report:
  1. Set date range (1st to last day)
  2. Click Export
  3. Open in Excel
  4. Submit to compliance

Investigation:
  1. Filter by user
  2. Click Export
  3. Analyze in spreadsheet
  4. Document findings

Backup:
  1. No filters (get all)
  2. Click Export
  3. Save securely
  4. Archive for records
```

---

## 🔍 Search & Discovery

### Search Methods

**By Date**

```
Filter by exact date
→ Set Start and End to same date

Filter by date range
→ Set Start date, End date
→ See all changes in range
```

**By User**

```
Select from dropdown
→ See only that user's actions
→ See what they changed when
```

**By Action Type**

```
Select: Created, Updated, or Deleted
→ See all of that type
→ Find all deletions, for example
```

**By Resource**

```
Enter resource type (Product, Order, etc.)
→ See all changes to that type
Or enter specific ID
→ See timeline of that record
```

**By Combination**

```
Date + User
→ See user's actions in period

Date + Action
→ See all deletions this week

Resource + Date
→ See timeline for one record
```

---

## 📊 Analytics Available

### Statistics Endpoint

```
GET /admin/audit-logs/statistics

Returns JSON:
{
    "total_entries": 1250,
    "total_users": 12,
    "total_resources": 45,
    "created_count": 400,
    "updated_count": 700,
    "deleted_count": 150,
    "last_entry": "2026-01-29 14:45:00",
    "first_entry": "2026-01-20 10:30:15"
}
```

---

## 🎓 Common Queries

### Query 1: Track Specific User

```sql
SELECT * FROM audit_logs
WHERE user_id = 5
ORDER BY created_at DESC
LIMIT 100;
```

### Query 2: Find Deleted Records

```sql
SELECT * FROM audit_logs
WHERE action = 'deleted'
AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY created_at DESC;
```

### Query 3: Resource Change History

```sql
SELECT * FROM audit_logs
WHERE model_type = 'App\Models\Product'
AND model_id = 42
ORDER BY created_at DESC;
```

### Query 4: Recent Changes by IP

```sql
SELECT ip_address, COUNT(*) as count
FROM audit_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
GROUP BY ip_address
ORDER BY count DESC;
```

---

## 🚀 Workflow Examples

### Workflow: Investigate Deletion

```
1. Open Audit Logs (/admin/audit-logs)
2. Set Action filter: "Deleted"
3. Set Start Date: Today
4. Set End Date: Today
5. Click "Apply Filters"
6. View list of today's deletions
7. Click eye icon on suspicious one
8. Review details:
   - Who deleted it?
   - When?
   - From what IP?
   - What was the data?
9. Click history icon
10. See complete timeline
11. Document findings
12. Report to management
```

### Workflow: User Activity Audit

```
1. Open Audit Logs
2. Select User: "John Doe"
3. Set Date range: Last month
4. Click "Apply Filters"
5. See all John's changes
6. Scan for unusual activity
7. Click details on suspicious
8. Verify intended changes
9. Check IP addresses
10. Export for documentation
11. Complete audit
12. Archive report
```

### Workflow: Compliance Export

```
1. Open Audit Logs
2. Set date range:
   - Start: 1st of month
   - End: Last day of month
3. Optional: Filter by action
4. Click "Export CSV"
5. Save file (audit-logs-jan.csv)
6. Import to compliance system
7. Run required checks
8. Generate report
9. Submit to auditor
10. Archive evidence
```

---

## ✅ Best Practices

### For Administrators

1. **Review Regularly**
    - Check weekly for patterns
    - Investigate anomalies immediately
    - Document findings

2. **Monitor Privileges**
    - Track admin account usage
    - Alert on admin deletions
    - Review escalations

3. **Export for Compliance**
    - Monthly exports
    - Quarterly reviews
    - Annual archival

4. **Investigate Anomalies**
    - Unusual IPs
    - Off-hours access
    - Bulk operations
    - User account changes

### For Security

1. **Alert Configuration**
    - Mass deletions
    - Unusual IPs
    - Privilege escalation
    - After-hours access

2. **Access Control**
    - Admin-only access
    - Separate audit account
    - MFA for audit access
    - Session logging

3. **Data Protection**
    - Encryption at rest
    - Encryption in transit
    - Secure backups
    - Retention policy

### For Compliance

1. **Documentation**
    - Monthly exports
    - Controlled access log
    - Retention schedule
    - Audit trail integrity

2. **Verification**
    - Quarterly reviews
    - Annual audits
    - External audits
    - Change validation

3. **Reporting**
    - Monthly summaries
    - Quarterly metrics
    - Annual compliance
    - Risk assessments

---

## 🔧 Maintenance

### Database Maintenance

```sql
-- Optimize table periodically
OPTIMIZE TABLE audit_logs;

-- Check index health
ANALYZE TABLE audit_logs;

-- Archive old logs (yearly)
DELETE FROM audit_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 YEAR);
```

### Performance Tuning

- Ensure indexes are active
- Monitor query times
- Archive old data
- Partition large tables
- Cache statistics

### Backup Strategy

- Daily incremental backups
- Weekly full backups
- Monthly archival
- Off-site storage
- Test recovery monthly

---

## 📞 Reference Quick Links

| Need            | Document            | Section          |
| --------------- | ------------------- | ---------------- |
| How to use      | QUICK_START         | "How to Filter"  |
| Visual demo     | FEATURE_SHOWCASE    | "Feature 1-10"   |
| Design specs    | DESIGN_GUIDE        | "Color Palette"  |
| Technical setup | IMPLEMENTATION      | "File Structure" |
| All docs        | DOCUMENTATION_INDEX | "Navigation"     |

---

**Reference Version**: 1.0.0
**Last Updated**: January 29, 2026
**Status**: Complete & Authoritative
