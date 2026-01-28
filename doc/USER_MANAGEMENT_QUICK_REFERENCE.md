# User Management - Quick Reference Card

## 🚀 Access the Page

```
URL: https://e-commerce.app/admin/users
Permission Required: Admin Role
```

---

## 📋 Feature Summary

| Feature         | Status | Details                             |
| --------------- | ------ | ----------------------------------- |
| User List Table | ✅     | 15 users per page, sortable columns |
| Role Filter     | ✅     | Admin, Staff, Vendor, Customer      |
| Status Filter   | ✅     | Active, Locked                      |
| Edit Role       | ✅     | Change user role with confirmation  |
| Lock Account    | ✅     | Disable user access                 |
| Unlock Account  | ✅     | Restore user access                 |
| Pagination      | ✅     | Full pagination controls            |
| Audit Logging   | ✅     | All actions logged                  |
| Self-Protection | ✅     | Cannot modify own account           |

---

## 🎨 Visual Elements at a Glance

### Role Badges

| Role     | Color     | Icon     |
| -------- | --------- | -------- |
| Admin    | 🟣 Purple | Crown    |
| Staff    | 🔵 Blue   | User Tie |
| Vendor   | 🟠 Amber  | Store    |
| Customer | 🟢 Green  | User     |

### Status Badges

| Status | Color    | Meaning     |
| ------ | -------- | ----------- |
| Active | 🟢 Green | Operational |
| Locked | 🔴 Red   | Restricted  |

### Action Icons

| Action    | Icon | Color | Function       |
| --------- | ---- | ----- | -------------- |
| Edit Role | ✏️   | Blue  | Change role    |
| Lock      | 🔒   | Red   | Disable access |
| Unlock    | 🔓   | Green | Enable access  |
| View      | 👁️   | Gray  | Full profile   |

---

## 🔌 API Endpoints

### RESTful Routes

```
GET    /admin/users                    → List users (with filters)
POST   /admin/users                    → Create new user
GET    /admin/users/create             → Create form
GET    /admin/users/{id}               → View user details
GET    /admin/users/{id}/edit          → Edit form
PATCH  /admin/users/{id}               → Update user info
PATCH  /admin/users/{id}/toggle-status → Lock/unlock account
PATCH  /admin/users/{id}/update-role   → Change user role
DELETE /admin/users/{id}               → Delete user
```

---

## 📝 Query Parameters

### Filtering

```
?role=1              → Filter by role (1=admin, 2=staff, 3=customer, 4=vendor)
?status=active       → Show only active users
?status=locked       → Show only locked users
?role=2&status=active → Combined filters
?page=2              → Pagination
```

### Examples

```
/admin/users?role=1
/admin/users?status=active
/admin/users?role=1&status=active&page=2
```

---

## 🧪 Test Scenarios

### Scenario 1: Filter Admins

1. Go to `/admin/users`
2. Select "admin" in role filter
3. Click "Apply Filters"
4. ✅ See only admin users

### Scenario 2: Lock a User

1. Find any non-admin user
2. Click 🔒 icon
3. Confirm in modal
4. ✅ User status → Red "Locked"
5. Icon changes to 🔓

### Scenario 3: Change User Role

1. Click ✏️ icon on any user
2. Select new role
3. Click "Update Role"
4. ✅ Role badge updates
5. Success message appears

### Scenario 4: Self-Protection Test

1. Find your own user
2. Try locking (should be disabled 🚫)
3. Try changing role (modal opens but blocked)
4. ✅ Cannot modify yourself

---

## 💾 Database Fields Used

```sql
users.id              -- User ID
users.name            -- User name (display name)
users.email           -- Email address
users.role_id         -- Foreign key to roles
users.is_active       -- Boolean: account status
users.created_at      -- Registration timestamp
```

---

## 🔐 Security Checklist

- ✅ Admin-only access (middleware)
- ✅ Self-protection (cannot modify own account)
- ✅ Audit logging (all actions tracked)
- ✅ CSRF protection (Laravel default)
- ✅ Validation (server-side)
- ✅ Confirmation modals (prevent accidents)
- ✅ Error handling (user-friendly messages)

---

## 📊 Role ID Reference

```javascript
const roles = {
    1: "admin",
    2: "staff",
    3: "customer",
    4: "vendor",
};
```

---

## 🎯 Common Tasks

### How to Filter Users

```
1. Open /admin/users
2. Select role or status
3. Click "Apply Filters"
4. (Optional) Click "Reset" to clear
```

### How to Lock a User

```
1. Find user in list
2. Click 🔒 lock icon
3. Read warning
4. Click "Lock Account" button
5. User is now locked (red badge)
```

### How to Unlock a User

```
1. Filter by status "locked"
2. Find user
3. Click 🔓 unlock icon
4. Confirm action
5. User is now active (green badge)
```

### How to Change User Role

```
1. Click ✏️ edit icon
2. Select new role from dropdown
3. Click "Update Role"
4. Role badge updates immediately
5. Action is logged to audit trail
```

---

## ⚠️ Limitations & Restrictions

1. **Cannot Lock Yourself**
    - Lock icon disabled if it's your own account
    - Shows 🚫 ban icon instead

2. **Cannot Change Your Role**
    - Modal opens but submit is blocked
    - Error message: "You cannot change your own role!"

3. **Cannot Delete Yourself**
    - Delete action prevented for own account
    - Error message: "You cannot delete yourself!"

4. **Pagination**
    - 15 users per page (configurable in controller)
    - Total users shown in header

---

## 🔧 Configuration

### Items Per Page

**Location**: `app/Http/Controllers/Admin/UserController.php`

```php
$users->paginate(15); // Change 15 to desired number
```

### Role Badges (Add More Roles)

**Location**: `resources/views/admin/users/index.blade.php`

```php
$roleBadges = [
    'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'crown'],
    // Add new roles here
];
```

---

## 📱 Responsive Behavior

| Screen Size         | Behavior                                |
| ------------------- | --------------------------------------- |
| Mobile (<768px)     | Vertical layout, touch-friendly buttons |
| Tablet (768-1024px) | 2-column filters, responsive table      |
| Desktop (>1024px)   | 3-column filters, full-width table      |

---

## 🐛 Troubleshooting

| Issue                  | Solution                               |
| ---------------------- | -------------------------------------- |
| Filters not working    | Clear browser cache, refresh page      |
| Modal won't close      | Press Escape key or click Cancel       |
| Buttons disabled       | Check user permission (admin required) |
| No users showing       | Verify filters applied, check database |
| Audit logs not created | Check Auditable trait is enabled       |

---

## 📚 Documentation Links

- **Design Guide**: `doc/USER_MANAGEMENT_DESIGN.md`
- **Full Guide**: `doc/USER_MANAGEMENT_GUIDE.md`
- **Visual Reference**: `doc/USER_MANAGEMENT_VISUAL_GUIDE.md`
- **User Model**: `app/Models/User.php`
- **Controller**: `app/Http/Controllers/Admin/UserController.php`

---

## 🏆 Best Practices

### For Admins

1. ✅ Regularly review active users
2. ✅ Lock suspicious accounts immediately
3. ✅ Document role changes
4. ✅ Monitor audit logs weekly
5. ✅ Keep at least one admin account active

### For Developers

1. ✅ Don't bypass self-protection checks
2. ✅ Always log sensitive operations
3. ✅ Test with multiple roles
4. ✅ Validate before saving
5. ✅ Show clear feedback messages

---

## 📞 Support Contacts

- **Issue**: Bugs or unexpected behavior → Check browser console
- **Feature Request**: Comment in code or create issue
- **Access Denied**: Verify admin role → `artisan tinker` → Check role_id

---

**Version**: 1.0.0
**Last Updated**: January 29, 2026
**Status**: ✅ Production Ready
