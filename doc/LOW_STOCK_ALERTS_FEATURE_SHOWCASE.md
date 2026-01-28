# Low Stock Alerts - Feature Showcase

## 🎯 Feature #1: Alert Summary Cards

### What It Does

Displays 4 overview cards showing inventory health at a glance. Each card shows a key metric with color-coding and icon.

### The 4 Cards Explained

#### Card 1: Critical Items (Red)

```
╔════════════════════════╗
║ ⚠️  CRITICAL ITEMS     ║
║                        ║
║         8              ║
║                        ║
║ Immediate action       ║
║ needed                 ║
╚════════════════════════╝
```

**Visual Design**:

- Background: Light red (red-50: #FEF2F2)
- Border: 4px red line (red-600: #DC2626)
- Icon: Exclamation circle in red
- Number: 8 (large, bold, red)
- Label: "CRITICAL ITEMS" (red)
- Description: "Immediate action needed" (gray)

**Real-World Scenario**:

- You have 8 products below 50% of minimum stock
- These are the most urgent items
- Click "Restock All Critical" button to act on all 8 at once
- Or handle individually with red "Restock Now" buttons

**Example Products**:

1. Premium Wireless Headphones: 12 units (minimum: 50)
2. Smart Watch Pro: 8 units (minimum: 45)
3. Desk Lamp LED: 15 units (minimum: 60)
4. Wireless Mouse USB: 5 units (minimum: 30)
5. Monitor Stand Adjustable: 7 units (minimum: 25)
6. USB-C Cable 3-pack: 20 units (minimum: 80)
7. Mechanical Keyboard RGB: 3 units (minimum: 20)
8. Phone Screen Protector: 18 units (minimum: 100)

**User Interaction**:

```
Morning check:
1. See card shows 8 critical items
2. Click "Restock All Critical (8 items)" quick action
3. Confirm bulk restock
4. All 8 items marked for immediate restocking
Time taken: 30 seconds
```

---

#### Card 2: Warning Items (Orange)

```
╔════════════════════════╗
║ ⚠️  WARNING ITEMS      ║
║                        ║
║         14             ║
║                        ║
║ Plan restocking       ║
║ soon                   ║
╚════════════════════════╝
```

**Visual Design**:

- Background: Light orange (orange-50: #FFF7ED)
- Border: 4px orange line (orange-500: #F97316)
- Icon: Exclamation mark in orange
- Number: 14 (large, bold, orange)
- Label: "WARNING ITEMS" (orange)
- Description: "Plan restocking soon" (gray)

**Real-World Scenario**:

- You have 14 products at 51-80% of minimum stock
- Not immediately critical, but getting close
- Plan restocking for the next few days
- Use "Schedule Restock" button to set a date
- Or wait for them to become critical

**Example Products**:

1. Cotton T-Shirt Bundle: 125 units (minimum: 150) - 83%
2. Yoga Mat Set: 98 units (minimum: 120) - 82%
3. Running Shorts Pack: 145 units (minimum: 175) - 83%
4. Leggings Premium: 189 units (minimum: 225) - 84%
5. Sports Socks Pack: 234 units (minimum: 280) - 84%
6. Hoodie Lightweight: 167 units (minimum: 200) - 84%
7. Winter Hat Fleece: 156 units (minimum: 190) - 82%
8. Gym Towel Set: 198 units (minimum: 240) - 83%
   (Plus 6 more...)

**User Interaction**:

```
Weekly planning:
1. See card shows 14 warning items
2. Filter status = "Warning" to see just these
3. Review list and group by supplier
4. Click "Schedule Restock" for each group
5. Set delivery dates 3-5 days out
Time taken: 10 minutes
```

---

#### Card 3: Low Items (Yellow)

```
╔════════════════════════╗
║ 🔔 LOW ITEMS          ║
║                        ║
║         6              ║
║                        ║
║ Monitor closely       ║
╚════════════════════════╝
```

**Visual Design**:

- Background: Light yellow (yellow-50: #FEFCE8)
- Border: 4px yellow line (yellow-500: #EAB308)
- Icon: Bell icon in yellow
- Number: 6 (large, bold, yellow)
- Label: "LOW ITEMS" (yellow)
- Description: "Monitor closely" (gray)

**Real-World Scenario**:

- You have 6 products at 81-100% of minimum stock
- Still adequate, but worth watching
- Usually no action needed yet
- Use "Monitor" button to set reminders
- Or configure threshold if you think it's too high

**Example Products**:

1. Running Shoes Premium: 189 units (minimum: 200) - 95%
2. Winter Jacket Black: 156 units (minimum: 160) - 97%
3. Casual Jeans Blue: 234 units (minimum: 240) - 97%
4. Summer Dress Floral: 178 units (minimum: 185) - 96%
5. Casual Shoes Leather: 145 units (minimum: 150) - 97%
6. Weekend Pack Backpack: 98 units (minimum: 100) - 98%

**User Interaction**:

```
Daily monitoring:
1. See card shows 6 low items
2. Glance at list to ensure not changing
3. Click "Monitor" button on any that change trend
4. Check again tomorrow
Time taken: 2 minutes
```

---

#### Card 4: Watched Items (Blue)

```
╔════════════════════════╗
║ 📦 WATCHED ITEMS      ║
║                        ║
║         28             ║
║                        ║
║ Products monitored    ║
╚════════════════════════╝
```

**Visual Design**:

- Background: Light blue (blue-50: #EFF6FF)
- Border: 4px blue line (blue-500: #3B82F6)
- Icon: Cubes icon in blue
- Number: 28 (large, bold, blue)
- Label: "WATCHED ITEMS" (blue)
- Description: "Products monitored" (gray)

**Real-World Scenario**:

- Total of all low stock products: 28 items
- This is: 8 critical + 14 warning + 6 low = 28
- Shows overall inventory health status
- If this number is high, you need more suppliers
- If this number is low, inventory is healthy

**Calculation**:

```
Critical: 8
+ Warning: 14
+ Low: 6
─────────
Total: 28
```

**Trend Analysis**:

- Last week: 25 items
- This week: 28 items (+3)
- Trend: Inventory tightening
- Action: Increase order frequency

**User Interaction**:

```
Monthly review:
1. Compare this month's total vs. last month
2. If trending up: Need more suppliers
3. If trending down: Good supplier management
4. Track 28 as baseline
```

---

### Design Specifications

**Card Layout**:

- 4 columns on desktop (25% width each)
- 2 columns on tablet (50% width each)
- 1 column on mobile (100% width)
- Equal height (flexbox)
- Gap between cards: 1.5rem (24px)

**Card Element Spacing**:

```
┌─────────────────────────┐
│ Padding: 1.5rem (24px)  │
│                         │
│ Icon (24px)  Label      │
│              (14px)     │
│                         │
│     28                  │
│  (30px bold)            │
│                         │
│ Description             │
│ (14px gray)             │
│                         │
└─────────────────────────┘
```

**Icon Positioning**:

- Top-left of card
- 20% opacity background color (doesn't overwhelm)
- 24px × 24px size
- Vertical center alignment with label

**Color Application**:

- Border: Critical red, warning orange, low yellow, total blue
- Background: Matching 50-shade (soft version)
- Icon: Status color, 20% opacity
- Number: Status color, bold
- Label: Status color, semibold

---

## 🎯 Feature #2: Alert Table with Status Indicators

### What It Does

Main data table showing all low stock products with detailed information. Each row shows one product with its current status and recommended action.

### Table Structure

```
┌──────────┬─────────────────────┬──────────────┬──────────────┬─────────────┬──────────────┬────────┐
│ Status   │ Product Name        │ Current Stock│ Min. Threshold│ Stock Level │ Restock Qty  │ Action │
├──────────┼─────────────────────┼──────────────┼──────────────┼─────────────┼──────────────┼────────┤
│ CRITICAL │ Premium Headphones  │ 12 units    │ 50 units     │ [██] 24%   │ +100 units   │ 🔴     │
│ CRITICAL │ Smart Watch Pro     │ 8 units     │ 45 units     │ [██] 18%   │ +100 units   │ 🔴     │
│ WARNING  │ Cotton T-Shirt      │ 125 units   │ 150 units    │ [██████] 83%│ +100 units   │ 🟠     │
│ WARNING  │ Yoga Mat Set        │ 98 units    │ 120 units    │ [██████] 82%│ +80 units    │ 🟠     │
│ LOW      │ Running Shoes       │ 189 units   │ 200 units    │ [███████████] 95%│ +50 units│ 🟡     │
│ LOW      │ Winter Jacket       │ 156 units   │ 160 units    │ [███████████] 97%│ +50 units│ 🟡     │
│ CRITICAL │ Desk Lamp LED       │ 15 units    │ 60 units     │ [██] 25%   │ +150 units   │ 🔴     │
│ WARNING  │ Bluetooth Speaker   │ 85 units    │ 100 units    │ [██████] 85%│ +100 units   │ 🟠     │
└──────────┴─────────────────────┴──────────────┴──────────────┴─────────────┴──────────────┴────────┘
```

### Understanding Each Column

#### Column 1: Status Badge

```
CRITICAL  →  Red background, pulsing animation, exclamation icon
WARNING   →  Orange background, static, exclamation icon
LOW       →  Yellow background, static, bell icon
```

**Visual Details**:

- Pill shape (border-radius: 9999px)
- White text, bold font
- Icon + text (e.g., "⚠️ CRITICAL")
- Padding: 4px 12px (small but readable)
- Only "CRITICAL" pulses (animate-pulse)

**Example Reading**:

```
✓ If red with pulse → ACT NOW (0-50% of min)
✓ If orange static → Plan soon (51-80% of min)
✓ If yellow static → Monitor (81-100% of min)
```

---

#### Column 2: Product Name

```
Examples:
- Premium Wireless Headphones (Electronics)
- Cotton T-Shirt Bundle (Fashion)
- Yoga Mat Set (Home & Garden)
- Running Shoes Premium (Sports)
```

**Formatting**:

- Product name: 16px, bold
- Category: 14px, gray, in parentheses
- Both on same row, separated by space

**Interactive**:

- Click to open product details page
- Link color: blue-600
- Underline on hover

---

#### Column 3: Current Stock

```
Examples:
- 12 units
- 8 units
- 125 units
- 189 units
```

**Formatting**:

- Number in bold
- "units" in gray
- Right-aligned for easy comparison
- 14px font size

**Real-World Value**:

- Shows exact amount on hand
- Used to calculate urgency
- Basis for restock quantity

---

#### Column 4: Minimum Threshold

```
Examples:
- 50 units (minimum for headphones)
- 45 units (minimum for smartwatch)
- 150 units (minimum for t-shirt bundles)
- 200 units (minimum for shoes)
```

**Formatting**:

- Number in bold
- "units" in gray
- Right-aligned
- 14px font size

**How It's Set**:

- Defined per product (not global)
- Based on sales velocity
- Can be adjusted in "Configure Thresholds"
- Example calculation:
    - Average daily sales: 3 units
    - Lead time from supplier: 10 days
    - Minimum = (3 × 10) + 20% buffer = 36 → set to 40 units

---

#### Column 5: Stock Level (Progress Bar)

```
Critical (24%):   [████████] 24%
Warning (83%):    [████████████████████████] 83%
Low (95%):        [████████████████████████████] 95%
```

**Visual Design**:

- Horizontal bar, full column width
- Background: light gray (gray-200)
- Filled portion: color-coded (red/orange/yellow)
- Label: percentage shown to the right

**Formula**:

```
Stock Level % = (Current Stock / Minimum Threshold) × 100

Example: (12 / 50) × 100 = 24%
Meaning: "Only 24% of the minimum is on hand"
```

**Bar Color Mapping**:

```
0-50% → Red (#DC2626)       [████] RED
51-80% → Orange (#F97316)   [██████████] ORANGE
81-100% → Yellow (#EAB308)  [███████████] YELLOW
```

**Interactive Behavior**:

- Smooth transition when updating
- Animated width change (0.3s ease)
- Hover shows exact value tooltip (optional)

---

#### Column 6: Restock Quantity

```
Examples:
- +100 units
- +50 units
- +150 units
```

**Calculation**:

```
Restock Qty = Maximum Stock - Current Stock

Example:
- Maximum: 150 units (or 3x current minimum)
- Current: 12 units
- Restock: 150 - 12 = +138 → recommend +100 (simpler number)
```

**Real-World Usage**:

- Copy this number into your purchase order
- Ensures you get back to adequate levels
- Calculated automatically (but editable)

**Formatting**:

- Plus sign: "+100" (indicates increase)
- Bold number
- "units" in gray
- Right-aligned
- 14px font size

---

#### Column 7: Action Button

```
Critical → [Restock Now] (Red)
Warning  → [Schedule] (Orange)
Low      → [Monitor] (Yellow)
```

**Button Types**:

**Red "Restock Now" Button**:

```
Used for: Critical items (0-50%)
Action: Immediate restocking
Icon: Truck symbol
Text: "Restock Now"
Color: Red (#DC2626)
Hover: Darker red (#991B1B)
Click flow:
  1. Click button
  2. Confirm quantity (shows recommended)
  3. Creates purchase order
  4. Marks as "Restocking"
  5. Activity log updated
```

**Orange "Schedule" Button**:

```
Used for: Warning items (51-80%)
Action: Schedule restocking for future
Icon: Calendar symbol
Text: "Schedule"
Color: Orange (#F97316)
Hover: Darker orange (#EA580C)
Click flow:
  1. Click button
  2. Choose date picker
  3. Confirm schedule
  4. Sets reminder for that date
  5. Activity log updated
```

**Yellow "Monitor" Button**:

```
Used for: Low items (81-100%)
Action: Set monitoring/reminder
Icon: Eye symbol
Text: "Monitor"
Color: Yellow (#EAB308)
Hover: Darker yellow (#CA8A04)
Click flow:
  1. Click button
  2. Choose reminder frequency
  3. Confirm monitoring
  4. Will alert if drops to warning level
  5. Activity log updated
```

---

### Row Styling Example: Premium Headphones

```
╔════════════════════════════════════════════════════════════════════════╗
║ ║ CRITICAL │ Premium Headphones (Electronics) │ 12 units │ 50 units  ║
║ ║ │ [████] 24% │ +100 units │ [Restock Now] ║
║ ║════════════════════════════════════════════════════════════════════════║
  ↑
  4px left border (red)

Background: Light red (red-50)
Text: Dark gray on red tint
Hover: Slightly darker red
Pulsing badge: CRITICAL (opacity 1 → 0.5 → 1)
```

**Visual Hierarchy**:

- Pulsing red badge draws attention first
- Product name is most important info
- Stock numbers are supporting data
- Action button is clear next step

---

### Table Footer: Pagination

```
Showing 8 of 28 products   [View All →]
```

**Meaning**:

- Currently showing: 8 products
- Total available: 28 products
- Indicates: Filter is applied (status, category, or search)
- Action: Click "View All" to see remaining 20 items

**Example Scenarios**:

**Scenario 1**: Viewing Critical Only

```
Filter: Status = Critical
Showing: 8 of 28
Meaning: 8 critical items exist, 20 are warning/low
Click "View All" → See all critical items in current filter
```

**Scenario 2**: Viewing Electronics Category

```
Filter: Category = Electronics
Showing: 9 of 28
Meaning: 9 items are electronics, 19 are other categories
Click "View All" → See all electronics items
```

**Scenario 3**: Searching "Shirt"

```
Filter: Search = "Shirt"
Showing: 2 of 28
Meaning: 2 products match "Shirt", 26 don't
Click "View All" → See all matching products
```

---

## 🎯 Feature #3: Filter & Search Controls

### What It Does

Allows you to narrow down the table to specific products using status, category, sorting, and keyword search.

### Filter Controls Layout

```
┌─────────────────────────────────────────────────────────────────────┐
│ Status Filter   │ Category Filter   │ Sort By      │ Search...   │ ✓ │
│ [All Statuses ▼]│ [All Categories▼]│ [Urgency  ▼]│ [type name] │Apply│
└─────────────────────────────────────────────────────────────────────┘
```

---

### Filter #1: Status Filter

**Dropdown Options**:

```
○ All Statuses (default)
○ Critical (0-50%)
○ Warning (51-80%)
○ Low (81-100%)
```

**Example Usage**:

**Morning: Focus on Critical**

```
1. Click Status Filter dropdown
2. Select "Critical"
3. Click Apply
4. Table shows only 8 red items
5. You see exactly what needs attention
```

**Weekly: Plan Warnings**

```
1. Click Status Filter dropdown
2. Select "Warning"
3. Click Apply
4. Table shows 14 orange items
5. Schedule restocking for these
```

**Daily: Monitor Low**

```
1. Click Status Filter dropdown
2. Select "Low"
3. Click Apply
4. Table shows 6 yellow items
5. Ensure none became critical
```

**Default: All Statuses**

```
1. Click Status Filter dropdown
2. Select "All Statuses"
3. Click Apply
4. Table shows all 28 items
5. Full inventory health check
```

---

### Filter #2: Category Filter

**Dropdown Options**:

```
○ All Categories (default)
○ Electronics
○ Fashion
○ Home & Garden
○ Sports
```

**Products by Category**:

**Electronics** (9 items):

- Premium Wireless Headphones (critical)
- Smart Watch Pro (critical)
- Wireless Mouse USB (critical)
- Bluetooth Speaker (warning)
- Phone Screen Protector (low)
- Desk Lamp LED (critical)
- USB-C Cable 3-pack (critical)
- Mechanical Keyboard RGB (critical)
- Monitor Stand Adjustable (critical)

**Fashion** (7 items):

- Cotton T-Shirt Bundle (warning)
- Yoga Mat Set (warning) _technically home_
- Running Shorts Pack (warning)
- Leggings Premium (warning)
- Hoodie Lightweight (warning)
- Winter Hat Fleece (warning)
- Casual Jeans Blue (low)

**Home & Garden** (6 items):

- Yoga Mat Set (warning)
- Sports Socks Pack (warning)
- Gym Towel Set (warning)
- Kitchen Knife Set (warning)
- Pillow Memory Foam (warning)
- Bedsheet Cotton (low)

**Sports** (6 items):

- Running Shoes Premium (low)
- Winter Jacket Black (low)
- Summer Dress Floral (low)
- Casual Shoes Leather (low)
- Weekend Pack Backpack (low)
- Sports Water Bottle (low)

**Example Usage**:

**Restocking by Category**:

```
Monday: Electronics restocking
1. Click Category Filter = "Electronics"
2. Click Apply
3. See 9 electronics items
4. Order from electronics suppliers
5. Mark as ordered in system

Tuesday: Fashion restocking
1. Click Category Filter = "Fashion"
2. Click Apply
3. See 7 fashion items
4. Order from fashion suppliers
5. Mark as ordered in system
```

---

### Filter #3: Sort Options

**Sort Dropdown Options**:

```
○ Urgency (default)
  Shows: Critical first, then warning, then low
  Behavior: Critical items at top

○ Stock Level
  Shows: Lowest stock first (3%) to highest (97%)
  Behavior: Most critical at top

○ Product Name
  Shows: A-Z alphabetical order
  Behavior: "Bluetooth Speaker" before "Cotton T-Shirt"

○ Restock Quantity
  Shows: Highest restock needs first
  Behavior: Items needing +150 units before +50 units
```

**Example Usage**:

**Sort by Urgency** (default):

```
Shows order:
1. Mechanical Keyboard RGB (3%) - CRITICAL
2. Wireless Mouse USB (5%) - CRITICAL
3. Smart Watch Pro (8%) - CRITICAL
4. Premium Headphones (12%) - CRITICAL
... (rest of critical)
... (all warning)
... (all low)

Good for: Quick action, handle most urgent first
```

**Sort by Stock Level**:

```
Shows order (from lowest %):
1. Mechanical Keyboard RGB (3%)
2. Wireless Mouse USB (5%)
3. Smart Watch Pro (8%)
4. Premium Headphones (12%)
... ascending to...
97. Weekend Pack Backpack (98%)
98. Winter Jacket Black (97%)
99. Casual Shoes Leather (97%)

Good for: Seeing absolute lowest stock
```

**Sort by Product Name**:

```
Shows order:
1. Bedsheet Cotton
2. Bluetooth Speaker
3. Casual Jeans Blue
4. Casual Shoes Leather
5. Cotton T-Shirt Bundle
... alphabetically...
28. Winter Hat Fleece

Good for: Finding specific product
```

**Sort by Restock Quantity**:

```
Shows order (highest → lowest):
1. Premium Headphones (+150 units)
2. Desk Lamp LED (+150 units)
3. USB-C Cable 3-pack (+130 units)
... descending to...
6. Weekend Pack Backpack (+15 units)

Good for: Understanding total restock needs
```

---

### Filter #4: Search by Product Name

**How It Works**:

```
Type in search field → Results filter in real-time (or on Apply)

Examples:
- "Shirt" → Cotton T-Shirt Bundle appears
- "Shoes" → Running Shoes, Casual Shoes appear
- "Yoga" → Yoga Mat Set appears
- "Premium" → Premium Headphones, Leggings Premium appear
```

**Search Examples**:

**Search: "Headphones"**

```
Enters: "Headphones"
Results:
- Premium Wireless Headphones (critical)

Count: Showing 1 of 28
```

**Search: "Watch"**

```
Enters: "Watch"
Results:
- Smart Watch Pro (critical)

Count: Showing 1 of 28
```

**Search: "Shirt"**

```
Enters: "Shirt"
Results:
- Cotton T-Shirt Bundle (warning)

Count: Showing 1 of 28
```

**Search: "Yoga"**

```
Enters: "Yoga"
Results:
- Yoga Mat Set (warning)

Count: Showing 1 of 28
```

---

### Combined Filter Examples

#### Example 1: Critical Electronics

```
Status Filter: Critical
Category Filter: Electronics
Sort By: Urgency
Search: (empty)

Results: 6 critical electronics items
1. Mechanical Keyboard RGB (3%)
2. Wireless Mouse USB (5%)
3. Premium Headphones (24%)
4. Desk Lamp LED (25%)
5. USB-C Cable 3-pack (25%)
6. Monitor Stand Adjustable (28%)

Use Case: Restock electronics supplier immediately
```

#### Example 2: Warning Fashion Items

```
Status Filter: Warning
Category Filter: Fashion
Sort By: Stock Level
Search: (empty)

Results: 5 warning fashion items
1. Hoodie Lightweight (82%)
2. Winter Hat Fleece (82%)
3. Leggings Premium (84%)
4. Running Shorts Pack (83%)
5. Cotton T-Shirt Bundle (83%)

Use Case: Schedule fashion restocking for next week
```

#### Example 3: Low Products Needing Monitoring

```
Status Filter: Low
Category Filter: All Categories
Sort By: Stock Level
Search: (empty)

Results: 6 low stock items (all need monitoring)
1. Bedsheet Cotton (81%)
2. Casual Jeans Blue (82%)
... all the way to...
6. Weekend Pack Backpack (98%)

Use Case: Daily monitoring to catch drops to warning level
```

#### Example 4: Find Product by Name

```
Status Filter: All Statuses
Category Filter: All Categories
Sort By: Urgency
Search: "Lamp"

Results: 1 product
- Desk Lamp LED (critical, 25%)

Use Case: Quickly locate specific product's status
```

---

## 🎯 Feature #4: Quick Actions Panel

### What It Does

Provides 4 bulk action buttons for common workflows. Faster than handling items individually.

### The 4 Quick Actions

#### Action 1: Restock All Critical

```
╔═══════════════════════════════════════════╗
║ [Restock All Critical (8 items)]          ║
╚═══════════════════════════════════════════╝
```

**Button Style**:

- Background: Red (#DC2626)
- Hover: Darker red (#991B1B)
- Text: White, bold
- Icon: Truck symbol
- Width: 100% (full panel width)
- Height: 48px (easy to tap)

**What It Does**:

```
Clicking → Bulk restock all 8 critical items at once
Instead of: Clicking "Restock Now" 8 separate times
Time saved: 5-7 minutes per use
```

**Products Affected** (8 items):

1. Mechanical Keyboard RGB (3%)
2. Wireless Mouse USB (5%)
3. Smart Watch Pro (8%)
4. Premium Headphones (12%)
5. Desk Lamp LED (15%)
6. USB-C Cable 3-pack (20%)
7. Monitor Stand Adjustable (28%)
8. (One more critical item)

**Workflow Example**:

```
Morning routine:
1. Open Low Stock Alerts dashboard
2. See "8 critical items" card
3. Click "Restock All Critical (8 items)"
4. Confirm action (yes/no popup)
5. System creates 8 purchase orders
6. All items marked "Restocking"
7. Activity log shows all 8 orders created
8. Done in 1 minute

vs.

Individual approach:
1. Click "Restock Now" on item 1, confirm
2. Click "Restock Now" on item 2, confirm
... repeat 8 times total...
= 10 minutes of clicking
```

---

#### Action 2: Schedule Warning Items

```
╔═══════════════════════════════════════════╗
║ [Schedule Restock - Warning (14 items)]   ║
╚═══════════════════════════════════════════╝
```

**Button Style**:

- Background: Orange (#F97316)
- Hover: Darker orange (#EA580C)
- Text: White, bold
- Icon: Calendar symbol
- Width: 100%
- Height: 48px

**What It Does**:

```
Clicking → Schedule all 14 warning items for future restocking
Instead of: Clicking "Schedule" 14 separate times
Time saved: 10-15 minutes per use
```

**Products Affected** (14 items):
All products at 51-80% of minimum stock across all categories

**Workflow Example**:

```
Weekly planning (Friday afternoon):
1. Click "Schedule Restock - Warning (14 items)"
2. Date picker appears
3. Choose delivery date: Next Tuesday
4. Confirm action
5. System schedules all 14 items for next Tuesday delivery
6. Activity log shows all scheduled
7. Reminders set for 1 day before delivery
8. Done in 2 minutes

vs. Manual:
1. Select 14 items individually... takes 15 minutes
```

---

#### Action 3: Review Low Items

```
╔═══════════════════════════════════════════╗
║ [Review Low Items (6 items)]              ║
╚═══════════════════════════════════════════╝
```

**Button Style**:

- Background: Yellow (#EAB308)
- Hover: Darker yellow (#CA8A04)
- Text: White, bold
- Icon: Eye/binoculars symbol
- Width: 100%
- Height: 48px

**What It Does**:

```
Clicking → Filters table to show ONLY the 6 low items
Lets you review them as a group
Decide if any need attention
Set monitoring for concerning items
```

**Products Affected** (6 items):

1. Running Shoes Premium (95%)
2. Winter Jacket Black (97%)
3. Casual Jeans Blue (82%)
4. Casual Shoes Leather (97%)
5. Weekend Pack Backpack (98%)
6. Bedsheet Cotton (81%)

**Workflow Example**:

```
Daily check (2 minutes):
1. Click "Review Low Items (6 items)"
2. Table filters to show only 6 yellow items
3. Scan through - none seem to be dropping
4. All are stable at 80-98%
5. No action needed today
6. Move on with rest of day

vs. Manual:
1. Manually filter status = low ... takes 5 clicks
```

---

#### Action 4: Configure Thresholds

```
╔═══════════════════════════════════════════╗
║ [Configure Thresholds]                    ║
╚═══════════════════════════════════════════╝
```

**Button Style**:

- Background: Blue (#3B82F6)
- Hover: Darker blue (#2563EB)
- Text: White, bold
- Icon: Settings/gear symbol
- Width: 100%
- Height: 48px

**What It Does**:

```
Clicking → Opens settings modal
Allows adjusting minimum threshold for each product
Recalculates all statuses automatically
Affects all future alerts
```

**When to Use**:

```
Scenario 1: Too many false alarms
  - Current threshold: 50 units for headphones
  - But only sell 2 per day
  - Reduce to: 30 units
  - Reduces unnecessary restock orders

Scenario 2: Predicting shortage
  - Current threshold: 150 units for t-shirts
  - Sales just increased to 5/day (from 2/day)
  - Increase to: 200 units
  - Prevents stockouts

Scenario 3: Seasonal adjustment
  - Current threshold: 100 units for shorts
  - Season change: winter coming, fewer shorts sales
  - Reduce to: 60 units
  - Matches new demand pattern
```

**Workflow Example**:

```
Monthly review (10 minutes):
1. Click "Configure Thresholds"
2. Modal opens with all 28 products
3. Review each threshold:
   - Headphones: 50 → 40 (reducing by 20%)
   - T-Shirts: 150 → 180 (increasing by 20%)
   - Running Shoes: 200 → 220 (increasing by 10%)
4. Save changes
5. All statuses recalculate
6. Activity log notes threshold changes
```

---

## 🎯 Feature #5: Recent Activity Log

### What It Does

Timeline showing all recent inventory actions. Helps you track what's been done and by whom.

### Activity Timeline

```
╔═══════════════════════════════════════════════════════════════╗
║ Recent Activity                                               ║
├───────────────────────────────────────────────────────────────┤
║ 🔴 Critical Alert: Premium Headphones                2 min ago ║
║    Stock dropped below 50 units, immediate action needed      ║
│                                                               ║
║ 🟠 Critical Alert: Smart Watch Pro                   15 min ago║
║    Only 8 units remain, order immediately                    ║
│                                                               ║
║ 🟠 Warning: Cotton T-Shirt Bundle                    45 min ago║
║    Stock at 83% of minimum, schedule restocking              ║
│                                                               ║
║ 🟢 Restock Completed: Yoga Mat Set              2 hours ago ║
║    200 units received and added to inventory                 ║
│                                                               ║
║ 🔵 Threshold Updated: Running Shoes               1 day ago ║
║    Minimum threshold changed from 200 to 220 units           ║
└───────────────────────────────────────────────────────────────┘
```

### Activity Types

#### 🔴 Critical Alert (Red)

```
Icon: Red circle/dot
Meaning: Product dropped below 50% of minimum
Action: Immediate restocking needed
Example: "Critical Alert: Premium Headphones - 2 min ago"
         "Stock dropped below 50 units, immediate action needed"
```

#### 🟠 Warning Alert (Orange)

```
Icon: Orange circle/dot
Meaning: Product at 51-80% of minimum
Action: Schedule restocking
Example: "Warning: Cotton T-Shirt Bundle - 45 min ago"
         "Stock at 83% of minimum, schedule restocking"
```

#### 🟡 Low Alert (Yellow)

```
Icon: Yellow circle/dot
Meaning: Product at 81-100% of minimum
Action: Monitor
Example: "Low: Winter Jacket - 3 hours ago"
         "Stock at 97%, monitor for changes"
```

#### 🟢 Restock Completed (Green)

```
Icon: Green circle/dot
Meaning: Restocking order completed and received
Action: None (inventory updated)
Example: "Restock Completed: Yoga Mat Set - 2 hours ago"
         "200 units received and added to inventory"
```

#### 🔵 Threshold Updated (Blue)

```
Icon: Blue circle/dot
Meaning: Minimum threshold changed for a product
Action: None (affects future alerts)
Example: "Threshold Updated: Running Shoes - 1 day ago"
         "Minimum threshold changed from 200 to 220 units"
```

#### 💜 Bulk Action (Purple)

```
Icon: Purple circle/dot
Meaning: Multiple items handled at once
Action: None (for tracking)
Example: "Bulk Restock: 8 Critical Items - 30 min ago"
         "All critical items marked for immediate restocking"
```

### Real-World Activity Log Example

**Full Day Timeline**:

```
09:00 AM - [🔴] Critical Alert: Smart Watch Pro
           "Only 8 units, need restock now"

09:02 AM - [🔴] Critical Alert: Premium Headphones
           "12 units remaining"

09:05 AM - [💜] Bulk Action: Restock All Critical
           "8 items queued for restocking"

09:30 AM - [🟢] Restock Completed: Smart Watch Pro
           "100 units received from supplier"

02:00 PM - [🟠] Warning Alert: Cotton T-Shirt Bundle
           "Stock at 83%, consider scheduling"

03:15 PM - [🔵] Threshold Updated: Running Shoes
           "Changed minimum from 200 → 220 units"

04:00 PM - [💜] Bulk Action: Schedule Restock - Warning
           "14 items scheduled for delivery Tuesday"
```

**Benefits of Activity Log**:

1. **Accountability**: See who did what and when
2. **Audit Trail**: Track all inventory changes
3. **Pattern Recognition**: Notice trends (e.g., "Headphones critical every Tuesday")
4. **Recovery**: Know what actions were taken if restock fails
5. **Planning**: Understand historical patterns

---

## 🎯 Feature #6: Color-Coded Status System

### Three-Level Urgency System

```
CRITICAL (Red)     →   0-50%   →   Action: RESTOCK NOW
WARNING (Orange)   →   51-80%  →   Action: SCHEDULE SOON
LOW (Yellow)       →   81-100% →   Action: MONITOR
```

### Why Three Levels?

**Prevents Alert Fatigue**:

- Not everything is "urgent"
- Clear distinction helps prioritization
- Two colors would be confusing
- Four+ colors would be overwhelming

**Status Calculation**:

```
Status Level % = (Current Stock / Minimum Threshold) × 100

If 0-50%:   RED/CRITICAL    → Urgent, act fast
If 51-80%:  ORANGE/WARNING  → Moderate, plan ahead
If 81-100%: YELLOW/LOW      → Stable, monitor only
```

### Examples with Percentages

#### Premium Headphones (CRITICAL)

```
Current: 12 units
Minimum: 50 units
Calculation: (12 ÷ 50) × 100 = 24%
Status: CRITICAL (red)
Urgency: Act immediately
Recommended Action: Click "Restock Now" button
```

#### Cotton T-Shirt Bundle (WARNING)

```
Current: 125 units
Minimum: 150 units
Calculation: (125 ÷ 150) × 100 = 83%
Status: WARNING (orange)
Urgency: Plan for next few days
Recommended Action: Click "Schedule" button
```

#### Running Shoes (LOW)

```
Current: 189 units
Minimum: 200 units
Calculation: (189 ÷ 200) × 100 = 95%
Status: LOW (yellow)
Urgency: Stable, just watch
Recommended Action: Click "Monitor" button
```

### Visual Differentiation

```
Color:     Red (#DC2626)      Orange (#F97316)    Yellow (#EAB308)
Meaning:   CRITICAL          WARNING              LOW
Icon:      ⚠️ Exclamation     ⚠️ Exclamation      🔔 Bell
Animation: Pulsing (blink)    Static              Static
Row bg:    Light red          Light orange        Light yellow
Button:    "Restock Now"      "Schedule"          "Monitor"
Card count: 8 items           14 items            6 items
Priority:  Handle first       Handle second       Handle if time
```

---

## Feature Usage Patterns

### Pattern 1: Morning Check (5 minutes)

```
1. Open dashboard
2. See 4 summary cards (8 critical, 14 warning, 6 low)
3. Click "Restock All Critical (8 items)"
4. Confirm bulk action
5. All critical items queued
6. Ready for rest of day
```

### Pattern 2: Weekly Planning (15 minutes)

```
1. Filter by Status = Warning
2. See 14 warning items
3. Group by supplier (mentally)
4. Click "Schedule Restock - Warning (14 items)"
5. Pick delivery date (next week)
6. Confirm scheduling
7. Reminders set automatically
```

### Pattern 3: Daily Monitoring (3 minutes)

```
1. Click "Review Low Items (6 items)"
2. Scan for changes
3. All stable at 80-100%
4. No action needed
5. Done
```

### Pattern 4: Monthly Adjustment (10 minutes)

```
1. Analyze last month's sales trends
2. Click "Configure Thresholds"
3. Adjust minimums based on demand
4. Save changes
5. All statuses recalculate
6. Activity logged
```

---

**Version**: 1.0.0  
**Updated**: January 29, 2026  
**Total Features**: 6 major, 20+ sub-features
