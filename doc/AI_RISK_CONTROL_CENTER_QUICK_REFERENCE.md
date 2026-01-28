# AI Risk Control Center - Quick Reference

## 🚀 Quick Start

**URL**: `https://e-commerce.app/admin/risk-rules`

**Access**: Admin users only

**Purpose**: Manage AI-powered fraud detection risk rules

---

## 📋 What You'll See

### Header Section

- **Title**: "AI Risk Control Center"
- **Subtitle**: "Intelligent fraud detection & adaptive risk management"
- **Icon**: Shield virus in gradient (red to pink)

### Statistics Cards

- Total Rules (Blue)
- Active Rules (Green)
- Average Weight (Purple)
- Disabled Rules (Red)

### Rule Cards

Each rule shows:

- Rule name (formatted)
- Risk level icon and badge
- Description
- Weight bar (0-100%)
- Status (Active/Inactive)
- Configure & Enable/Disable buttons
- Settings if available

---

## 🎯 Core Actions

### View Rules

1. Navigate to `/admin/risk-rules`
2. All active rules display as cards
3. Scroll to see all rules
4. Hover for shadow effect

### Configure Rule

1. Find target rule card
2. Click "Configure" button
3. Edit weight (0-100)
4. Update description
5. Click Save

### Enable/Disable Rule

1. Find target rule card
2. Click "Enable" or "Disable" button
3. Status updates immediately
4. Badge changes color

### Export Rules

1. Click "Export" button
2. JSON file downloads
3. Use for backup/transfer

### Import Rules

1. Click "Import" button
2. Select JSON file
3. Rules update/merge
4. Confirmation message

### Reset to Defaults

1. Click "Reset" button
2. Confirm action
3. All rules reset
4. Success notification

---

## 🎨 Risk Levels

| Level       | Icon     | Color  | Meaning        |
| ----------- | -------- | ------ | -------------- |
| 🔴 Critical | Skull    | Red    | Very High Risk |
| 🟠 High     | Triangle | Orange | High Risk      |
| 🟡 Medium   | Circle   | Amber  | Medium Risk    |
| 🟢 Low      | Shield   | Green  | Low Risk       |

---

## ⚖️ Weight System

- **0-20**: Low weight (minimal impact)
- **21-50**: Medium weight (moderate impact)
- **51-75**: High weight (significant impact)
- **76-100**: Critical weight (major impact)

Displayed as:

- Progress bar (visual gauge)
- Numeric value (percentage)
- Gradient color (blue)

---

## 🔘 Button Reference

| Button    | Color         | Action                 | Icon |
| --------- | ------------- | ---------------------- | ---- |
| Configure | Blue          | Edit rule settings     | ⚙️   |
| Enable    | Green         | Activate disabled rule | ✓    |
| Disable   | Amber         | Deactivate active rule | ⏻    |
| Import    | Blue outline  | Load from JSON         | ↓    |
| Export    | Green outline | Save to JSON           | ↓    |
| Reset     | Amber outline | Restore defaults       | ↻    |

---

## 📊 Statistics

### Total Rules

Count of all rules in system (active + inactive)

### Active Rules

Count of currently enabled rules

- Only these rules are evaluated
- Others are ignored

### Average Weight

Mean weight of all active rules

- Indicates overall risk threshold
- Ranges 0-100

### Disabled Rules

Count of inactive rules

- Can be re-enabled anytime
- Don't affect fraud detection

---

## 💾 Card Anatomy

### Left Section (70%)

- Icon + Name
- Description
- Weight bar
- Status badge

### Right Section (30%)

- Risk level badge
- Configure button
- Enable/Disable button

### Bottom (if settings exist)

- Settings section
- Key-value pairs
- Gray background

---

## 🔄 Workflow Examples

### Increase Risk Detection

```
1. Find rule → Click Configure
2. Increase weight (e.g., 20 → 40)
3. Select risk_level: "high"
4. Save changes
5. Rule now flagged more orders
```

### Disable Problematic Rule

```
1. Find rule causing false positives
2. Click "Disable" button
3. Confirm action
4. Badge changes to "Inactive"
5. Rule stops evaluating orders
```

### Export & Backup

```
1. Click "Export" button
2. rules-{date}.json downloads
3. Save to secure location
4. Use for disaster recovery
```

---

## ✅ Checklist: First Time

- [ ] Navigate to /admin/risk-rules
- [ ] See statistics cards at top
- [ ] Scroll and view all rule cards
- [ ] Hover over card (shadow appears)
- [ ] Click "Configure" on one rule
- [ ] Edit and save
- [ ] Return to list
- [ ] Toggle a rule on/off
- [ ] See badge update
- [ ] Export rules to JSON
- [ ] Check success message

---

## ⚙️ Common Tasks

### Find a Specific Rule

1. Use browser find (Ctrl+F)
2. Search rule name
3. Rule card highlighted
4. Click Configure

### Bulk Enable All

1. Click Reset button
2. Confirm action
3. All rules reset to active
4. All set to default weights

### Change Risk Level

1. Click Configure on rule
2. Change risk_level dropdown
3. Icon & color update
4. Save changes

### Add Custom Setting

1. Click Configure
2. In settings JSON add key-value
3. Save changes
4. Appears in card settings section

---

## 🚨 Common Issues

### Rules not showing

**Solution**: Run database seeder to populate initial rules

### Toggle not working

**Solution**: Refresh page, check for JS errors in console

### Import fails

**Solution**: Verify JSON format is valid, check file isn't corrupted

### Colors look wrong

**Solution**: Clear browser cache (Ctrl+Shift+Delete), refresh

---

## 🔐 Permissions

**Required Role**: Admin

**Allowed Actions**:

- View all rules ✅
- Edit rule weights ✅
- Toggle rules on/off ✅
- Import/export rules ✅
- Reset to defaults ✅

**Denied To**: Non-admin users

---

## 📱 Mobile View

- Single column layout
- Full-width cards
- Buttons stack vertically
- Touch-friendly spacing
- Same functionality as desktop

---

## 🎓 Tips & Tricks

1. **Hover effects**: Cards show shadow on hover for feedback
2. **Keyboard**: Tab through buttons, Enter to activate
3. **Export before reset**: Save current config before resetting
4. **Settings JSON**: Use for rule-specific configurations
5. **Weight priority**: Higher weight = higher risk score
6. **Disable first**: Test disabling before permanent removal

---

## 📞 Support Reference

### For Errors

1. Check browser console (F12)
2. Look for red error messages
3. Note the error text
4. Check troubleshooting section

### For Features

- Configure: Edit rule settings
- Toggle: Enable/disable rule
- Reset: Restore default config

### For Data

- Export: Backup current rules
- Import: Restore from backup
- Statistics: Monitor rule health

---

## 🏃 Quick Actions Menu

**View all**: Visit `/admin/risk-rules`
**Export**: Click "Export" button
**Import**: Click "Import" button
**Reset**: Click "Reset" button
**Edit**: Click "Configure" on any card
**Toggle**: Click "Enable" or "Disable"

---

**Last Updated**: January 29, 2026
**Version**: 1.0.0
**Status**: Production Ready
