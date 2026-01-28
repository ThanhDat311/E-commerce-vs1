# Low Stock Alerts - Quick Start Guide

## 🚀 Quick Start (5 minutes)

### Access the Dashboard

1. Navigate to: `/admin/low-stock-alerts`
2. You'll see all low stock products immediately
3. **4 summary cards** show overview (Critical: 8, Warning: 14, Low: 6)
4. **Full table** lists all products with low inventory
5. **Color coding** indicates urgency level

---

## 📊 Dashboard Overview

### What You See

- **4 Summary Cards**: Quick metrics for each alert level
- **Alert Table**: All products below minimum stock
- **Status Badges**: Color-coded by severity
- **Progress Bars**: Visual stock level indicator
- **Action Buttons**: Immediate restock options
- **Quick Actions**: Bulk operations for multiple items
- **Recent Activity**: Log of recent inventory changes

### How Colors Work

- **Red**: Critical (0-50% of minimum) - Action needed now
- **Orange**: Warning (51-80% of minimum) - Plan restocking
- **Yellow**: Low (81-100% of minimum) - Monitor closely

---

## 🎯 Quick Actions

### Filter by Status

**Steps**:

1. Click "Status Filter" dropdown (first field)
2. Select:
    - All Statuses (default)
    - Critical (0-50%)
    - Warning (51-80%)
    - Low (81-100%)
3. Click "Apply" button
4. Table updates immediately

**Use Case**: Focus on critical items only

---

### Filter by Category

**Steps**:

1. Click "Category" dropdown (second field)
2. Select:
    - All Categories (default)
    - Electronics
    - Fashion
    - Home & Garden
    - Sports
3. Click "Apply" button
4. See category-specific alerts

**Use Case**: Restocking specific product lines

---

### Change Sort Order

**Options**:

1. Click "Sort By" dropdown (third field)
2. Select:
    - **Urgency** (default): Critical first, then warning
    - **Stock Level**: Lowest stock first
    - **Product Name**: A-Z alphabetical
    - **Restock Qty**: Highest quantity needed first
3. Click "Apply" button

**Use Case**: Prioritize by urgency or other metrics

---

### Search Products

**Steps**:

1. Click Search field (fourth field)
2. Type product name
3. Click "Apply" button
4. Table filters in real-time
5. Leave empty to search again

**Examples**:

- "Headphones" → finds Premium Wireless Headphones
- "Yoga" → finds Yoga Mat Set
- "Desk" → finds Desk Lamp LED

---

### Take Action on Products

#### For Critical Items (Red)

1. Look for **red** "CRITICAL" badge
2. **Click "Restock Now" button** (red)
3. Confirm quantity (shows recommended amount)
4. Submit to create restock order
5. Item moves to "Completed" status

#### For Warning Items (Orange)

1. Look for **orange** "WARNING" badge
2. **Click "Schedule" button** (orange)
3. Choose restock date/time
4. Submit to schedule for later
5. Activity log updates

#### For Low Items (Yellow)

1. Look for **yellow** "LOW" badge
2. **Click "Monitor" button** (yellow)
3. Set reminder frequency (if enabled)
4. Will alert you when approaching critical
5. Keep watching

---

### Bulk Actions

**Restock All Critical Items**:

1. Left panel: Click "Restock All Critical (8 items)"
2. Confirm bulk action
3. All critical products marked for immediate restocking
4. Saves time vs. individual actions

**Schedule Warning Items**:

1. Left panel: Click "Schedule Restock - Warning (14 items)"
2. Choose restock date
3. All warning items scheduled
4. Prevents future critical situations

**Review Low Items**:

1. Left panel: Click "Review Low Items (6 items)"
2. Filters table to show only yellow items
3. Review each one
4. Decide if monitoring is sufficient

**Configure Thresholds**:

1. Left panel: Click "Configure Thresholds"
2. Opens settings modal
3. Adjust minimum stock levels
4. Save changes (affects all calculations)

---

## 📈 Understanding the Metrics

### Summary Cards

#### Critical Items (Red Card)

- **Count**: Number of products at 0-50% of minimum
- **Default**: 8 items
- **Meaning**: These need restocking RIGHT NOW
- **Action**: Use "Restock All Critical" button
- **Icon**: Exclamation circle (pulsing for attention)
- **Label**: "Immediate action needed"

#### Warning Items (Orange Card)

- **Count**: Number of products at 51-80% of minimum
- **Default**: 14 items
- **Meaning**: Will become critical soon
- **Action**: Plan restocking for near future
- **Icon**: Exclamation mark
- **Label**: "Plan restocking"

#### Low Items (Yellow Card)

- **Count**: Number of products at 81-100% of minimum
- **Default**: 6 items
- **Meaning**: Still adequate but worth watching
- **Action**: Monitor closely for changes
- **Icon**: Info circle
- **Label**: "Monitor closely"

#### Watched Items (Blue Card)

- **Count**: Total products with low stock being monitored
- **Default**: 28 items
- **Meaning**: Sum of critical + warning + low
- **Action**: Overall inventory health indicator
- **Icon**: Cubes
- **Label**: "Products monitored"

---

## 🔍 Reading the Alert Table

### Table Columns Explained

| Column         | What It Shows    | Example              | Action                |
| -------------- | ---------------- | -------------------- | --------------------- |
| Status         | Severity level   | "CRITICAL" (red)     | Match action to color |
| Product        | Item name        | "Premium Headphones" | Reference for orders  |
| Current Stock  | Units on hand    | 12 units             | Critical to act on    |
| Min. Threshold | Target minimum   | 50 units             | Sets safe level       |
| Stock Level    | Bar + percentage | 24% filled bar       | Visual urgency        |
| Restock Qty    | Amount to order  | +100 units           | Use in PO             |
| Action         | Primary button   | "Restock Now"        | Click to act          |

### Example Row Reading

```
Status: CRITICAL (red, pulsing)
Product: Premium Wireless Headphones (Electronics)
Current: 12 units
Minimum: 50 units
Level: 24% of minimum (red bar)
Restock: +100 units recommended
Action: "Restock Now" button (red)

Interpretation:
→ This product is critically low (only 12 of 50 units)
→ Recommend ordering 100 more units immediately
→ Click "Restock Now" to take action
```

---

## 📊 Understanding Stock Level Bars

### Visual Progress Bars

**What the Bar Shows**:

- **Length** = How much stock you have vs. minimum
- **Color** = Urgency level (red/orange/yellow)
- **Percentage** = Exact amount below minimum

**Examples**:

```
Premium Headphones: [████] 24% (RED, CRITICAL)
→ Only has 24% of minimum stock
→ Lost 76% of buffer
→ ACTION: Restock now

Cotton T-Shirt: [████████████] 83% (ORANGE, WARNING)
→ Has 83% of minimum stock
→ Lost 17% of buffer
→ ACTION: Schedule restocking soon

Running Shoes: [█████████████████] 95% (YELLOW, LOW)
→ Has 95% of minimum stock
→ Lost 5% of buffer
→ ACTION: Monitor, probably okay
```

---

## 💡 When to Take Each Action

### Use "Restock Now" When:

- Status is **RED/CRITICAL**
- Stock is below 50% of minimum
- Product is frequently bought
- You need stock urgently
- Example: Premium Headphones at 12 units (min: 50)

### Use "Schedule" When:

- Status is **ORANGE/WARNING**
- Stock is 51-80% of minimum
- You can wait a few days
- You want to batch order
- Example: T-Shirt Bundle at 125 units (min: 150)

### Use "Monitor" When:

- Status is **YELLOW/LOW**
- Stock is 81-100% of minimum
- You have time to react
- Trending upward (no concern)
- Example: Running Shoes at 189 units (min: 200)

---

## 📝 Common Questions

### Q1: What's the difference between Current Stock and Min. Threshold?

**A**:

- **Current**: What you have right now
- **Threshold**: What's the safe minimum
- **Example**: Current=12, Threshold=50, Recommendation=+100

### Q2: Why do some items say "Restock Now" and others "Schedule"?

**A**: Because they're at different urgency levels:

- **Red (Restock Now)**: Critically low, act fast
- **Orange (Schedule)**: Getting low, plan ahead
- **Yellow (Monitor)**: Still okay, but watch it

### Q3: How are percentages calculated?

**A**: `(Current Stock / Minimum Threshold) × 100`

- Example: (12 / 50) × 100 = 24%
- 24% means "only 24% of the minimum amount"

### Q4: Can I change minimum thresholds?

**A**: Yes! Click "Configure Thresholds" button:

1. Opens settings modal
2. Set new minimum for each product
3. Save changes
4. Alerts recalculate automatically

### Q5: How often does it update?

**A**:

- Manual filter: Instant
- Automatic refresh: Every 15 minutes
- Restock action: Updates within 1 minute
- Page refresh: Gets latest data

### Q6: What does "Restock Qty" mean?

**A**: The recommended amount to order to get back to safe levels:

- **Current**: 12, **Minimum**: 50, **Suggested**: +100
- Ordering 100 gets you to 112 (above minimum)

### Q7: How do I mass restock items?

**A**: Use bulk action buttons:

1. "Restock All Critical (8 items)" → immediate
2. "Schedule Restock - Warning (14 items)" → for later
3. Much faster than individual items

### Q8: What if I disagree with a product's status?

**A**: Adjust the minimum threshold:

1. Click "Configure Thresholds"
2. Change minimum value for that product
3. Status recalculates based on new threshold
4. May move to different alert level

---

## 🔌 Pro Tips & Tricks

### Tip 1: Use Status Filter for Focus

```
Morning routine:
1. Filter by Status = Critical
2. See only red items (8 products)
3. Use "Restock All Critical" button
4. Done in 2 minutes
```

### Tip 2: Track by Category

```
Tuesday: Review Electronics (9 items)
Wednesday: Review Fashion (7 items)
Thursday: Review Home & Garden (6 items)
Friday: Review Sports (6 items)
Spreads out the work
```

### Tip 3: Bulk Actions Save Time

```
Instead of clicking 8 individual "Restock Now" buttons:
→ Click "Restock All Critical (8 items)"
→ Saves 7 clicks and 5 minutes!
```

### Tip 4: Recent Activity = Truth

```
Don't remember what you did?
→ Check "Recent Activity" panel
→ Shows all actions with timestamps
→ Helps with inventory reconciliation
```

### Tip 5: Export Before Presentation

```
Need to share inventory status?
1. Set filters as needed
2. Click "Export" button
3. Open CSV in Excel
4. Create charts for management
```

---

## 📱 Mobile Tips

### Mobile Screen Optimization

- **Summary cards**: Stack vertically (easier to scroll)
- **Table**: Horizontal scroll (swipe left/right)
- **Buttons**: Large, easy to tap
- **Filters**: Collapsible on small screens

### Mobile Workflow

1. Filter by Critical (gets most urgent)
2. Scroll through products
3. Tap "Restock Now" for each
4. Confirm action on mobile popup
5. Move to next product

### Desktop vs. Mobile

- **Desktop**: Better for bulk actions and exports
- **Mobile**: Good for quick checks and individual actions
- **Best**: Use desktop for main work, mobile for alerts on-the-go

---

## 🆘 Troubleshooting

### Issue: Products not showing

**Solutions**:

1. Verify filters (might be filtered out)
2. Try "All Statuses" and "All Categories"
3. Refresh page
4. Check if products have minimum threshold set

### Issue: Percentages seem wrong

**Example**:

- Current: 12, Minimum: 50
- Showing: 24%
- Calculation: (12/50)\*100 = 24% ✓ Correct

**If still wrong**:

1. Check minimum threshold value
2. Verify current stock is accurate
3. Reload page

### Issue: Can't find a product

**Solutions**:

1. Use search box (third from bottom)
2. Type partial name
3. Try different filter combinations
4. Check if product still exists

### Issue: Actions not working

**Solutions**:

1. Ensure you're logged in
2. Check browser console for errors
3. Try refreshing page
4. Contact admin if still broken

---

## ⌨️ Quick Keys

No keyboard shortcuts configured yet. Current version uses mouse/touch only.

**Planned for next version**:

- `Alt+C` = Critical items
- `Alt+W` = Warning items
- `Alt+L` = Low items
- `Alt+E` = Export

---

## 📞 Need Help?

### Resources

- **Implementation Guide**: Deep technical details
- **Design Guide**: Visual and styling specs
- **Feature Showcase**: Real-world examples
- **Documentation Index**: Navigation and search

### Common Tasks

- **Find critical items**: Use status filter = Critical
- **Export data**: Click Export button, open in Excel
- **Adjust thresholds**: Click "Configure Thresholds"
- **See what changed**: Check "Recent Activity" section

---

**Version**: 1.0.0  
**Updated**: January 29, 2026  
**Read Time**: ~15 minutes
