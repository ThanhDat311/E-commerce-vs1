# Audit Logs - Quick Reference Guide

## 🚀 Quick Start

### Access the Page

```
URL: /admin/audit-logs
Admin menu: Settings → Audit Logs
```

### View All Logs

- Page loads with latest 25 entries
- Most recent first (newest at top)
- Shows: User, Action, Resource, IP, Timestamp

### Filter Records

**Option 1: By Date Range**

```
1. Set "Start Date" field
2. Set "End Date" field
3. Click "Apply Filters"
```

**Option 2: By User**

```
1. Select user from "User" dropdown
2. Click "Apply Filters"
```

**Option 3: By Action**

```
1. Select action (Created/Updated/Deleted)
2. Click "Apply Filters"
```

**Option 4: By Resource**

```
1. Enter resource type (e.g., "Product")
2. Enter resource ID
3. Click "Apply Filters"
```

**Option 5: Multiple Filters**

```
1. Set date range
2. Select user
3. Choose action
4. Enter resource
5. Click "Apply Filters"
```

### View Details

```
1. Find relevant log entry
2. Click eye icon (fa-eye)
3. See full details and changes
```

### View History

```
1. Find relevant log entry
2. Click history icon (fa-history)
3. See all changes to that resource
```

### Export Logs

```
1. (Optional) Apply filters first
2. Click "Export CSV" button
3. Download opens in spreadsheet
```

### Clear Filters

```
1. Click "Reset Filters" button
2. All filters clear
3. Page reloads with all entries
```

---

## 📋 Column Reference

| Column         | Content              | Format                       |
| -------------- | -------------------- | ---------------------------- |
| **Timestamp**  | When change occurred | `Mon DD, YYYY`<br>`HH:MM:SS` |
| **User**       | Who made the change  | Blue badge with name         |
| **Action**     | Type of change       | Green/Blue/Red badge         |
| **Resource**   | What was changed     | `Type`<br>`ID: ###`          |
| **IP Address** | Source IP address    | Monospace text               |
| **Details**    | Quick actions        | Eye icon, History icon       |

---

## 🎯 Common Tasks

### Find Who Created Product #42

```
1. Open /admin/audit-logs
2. Set Resource Type: Product
3. Set Resource ID: 42
4. Set Action: Created
5. Click "Apply Filters"
6. See creator in User column
```

### Track John Doe's Activity

```
1. Open /admin/audit-logs
2. Select User: John Doe
3. (Optional) Set date range
4. Click "Apply Filters"
5. See all John's actions
```

### Find All Deletions Today

```
1. Open /admin/audit-logs
2. Set Action: Deleted
3. Set Start Date: Today
4. Set End Date: Today
5. Click "Apply Filters"
```

### Get Monthly Compliance Report

```
1. Set Start Date: 1st of month
2. Set End Date: Last day of month
3. Click "Export CSV"
4. Opens in Excel/Sheets
5. Ready for audit
```

### Investigate Order Changes

```
1. Set Resource Type: Order
2. Set Resource ID: 1234
3. Click "Apply Filters"
4. See complete change history
5. Click details to see values
```

---

## 🎨 Color Codes

### Actions

- 🟢 **Green badge** = Created
- 🔵 **Blue badge** = Updated
- 🔴 **Red badge** = Deleted

### Users

- 🔵 **Blue badge** = Registered user
- ⚪ **Gray badge** = Deleted/Unknown user

---

## ⚡ Keyboard Shortcuts

| Shortcut                 | Action                  |
| ------------------------ | ----------------------- |
| `Enter` in filter field  | Apply filters           |
| `Escape` in filter field | Clear field             |
| `Tab`                    | Move to next filter     |
| `Shift+Tab`              | Move to previous filter |

---

## 🔍 Filter Operators

| Filter            | Operator            | Example                   |
| ----------------- | ------------------- | ------------------------- |
| **Date**          | Between (inclusive) | 2026-01-01 to 2026-01-31  |
| **User**          | Exact match         | John Doe                  |
| **Action**        | Exact match         | Created, Updated, Deleted |
| **Resource Type** | Partial match       | Product                   |
| **Resource ID**   | Exact match         | 42                        |

---

## 📊 Example Filters

### Last 7 Days

```
Start Date: [7 days ago]
End Date: [Today]
```

### Specific User, Specific Date

```
User: John Doe
Start Date: 2026-01-20
End Date: 2026-01-20
```

### All Deletions by Admin

```
User: Admin User
Action: Deleted
```

### Product Changes

```
Resource Type: Product
Resource ID: 42
(Shows all changes to product)
```

### No Filters

```
Click "Reset Filters"
(Shows latest 25 entries)
```

---

## 🔐 What You Can See

✅ Who made the change
✅ When the change happened
✅ What was changed
✅ What the old value was
✅ What the new value is
✅ Where the change came from (IP)
✅ Browser info (user agent)

---

## 🔒 What You CAN'T Do

❌ Edit audit log entries
❌ Delete audit log entries
❌ Hide audit log entries
❌ Modify timestamps
❌ Change user attribution

**This is intentional for security and compliance!**

---

## 📱 Mobile Friendly

- ✅ Responsive design
- ✅ Touch-friendly filters
- ✅ Scrollable table
- ✅ Works on tablets
- ✅ Works on phones

---

## 🐛 Troubleshooting

### No Results After Filter

- Check date range is valid
- Verify user exists
- Confirm resource type name
- Try clearing filters and retrying

### Can't Export CSV

- Check browser download permissions
- Ensure cookies enabled
- Try different browser if persistent
- Contact admin if continues

### Page Takes Long Time to Load

- Try filtering first
- Narrow date range
- Reduce number of entries
- Check server status

### Strange IP Address

- Could be proxy/VPN
- Could be load balancer
- Check user_agent for browser info

---

## 💡 Pro Tips

1. **Narrow dates first** → Faster results
2. **Use resource ID** → Find specific changes
3. **Export regularly** → Backup compliance data
4. **Check IP patterns** → Detect suspicious access
5. **Monitor deletions** → Security risk indicator
6. **Review user actions** → Audit privileged accounts
7. **Export month-end** → Monthly compliance report
8. **Set browser bookmark** → Quick access

---

## 🎯 Use Cases

| Use Case                   | Steps                                     |
| -------------------------- | ----------------------------------------- |
| **Compliance Audit**       | Set date range → Export CSV → Submit      |
| **Fraud Investigation**    | Set user → Set date → Review details      |
| **Change Tracking**        | Set resource → See timeline → Export      |
| **Access Review**          | Set user → Check all actions → Document   |
| **Security Investigation** | Set action (Deleted) → Review IPs → Alert |

---

## 📞 Getting Help

### For Detailed Info

→ See Implementation Guide

### For Setup Help

→ Ask your system administrator

### For Access Issues

→ Contact admin@yoursite.com

### Report a Bug

→ Submit to IT support with:

- Browser name/version
- Date and time
- Screenshot
- Steps to reproduce

---

**Last Updated**: January 29, 2026
**Version**: 1.0.0
