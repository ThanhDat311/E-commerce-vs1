# Audit Logs - Design Guide

## 🎨 Design Philosophy

### Core Principles

**1. Minimalism**

- Only essential information visible
- No decorative elements
- Clean, uncluttered layout
- Focus on data clarity

**2. Seriousness**

- Professional color palette
- Formal typography
- Business-focused styling
- Compliance-oriented aesthetic

**3. Trustworthiness**

- Clear, honest data display
- Transparent filtering
- Immutable records
- Security-focused design

**4. Accessibility**

- High contrast colors
- Clear typography
- Keyboard navigation
- Semantic HTML

---

## 🎨 Visual Hierarchy

### Level 1: Page Header

```
[Shield Icon] Audit Logs
Compliance & security audit trail
```

- **Size**: Large, prominent
- **Color**: Dark slate (slate-900)
- **Purpose**: Clear page identity
- **Element**: Icon + title + subtitle

### Level 2: Filter Section

```
FILTER LOGS
[Date inputs] [User dropdown] [Action dropdown]
[Resource Type] [Resource ID] [Apply] [Reset]
```

- **Size**: Medium
- **Color**: Gray backgrounds (subtle)
- **Purpose**: Control and filtering
- **Element**: Organized input fields

### Level 3: Table Header

```
Showing 1 to 25 of 150 entries
Timestamp | User | Action | Resource | IP | Details
```

- **Size**: Small, compact
- **Color**: Gray-600
- **Purpose**: Data information
- **Element**: Column headers + count

### Level 4: Table Rows

```
Jan 29, 2026 | John Doe | Created | Product #42 | 192.168.1.1 | [icons]
14:30:45
```

- **Size**: Standard reading size
- **Color**: Varies by content
- **Purpose**: Detailed data
- **Element**: Actual log entries

---

## 🎨 Color Palette

### Neutral Colors (Primary)

```
Slate-900   #0f172a  Title, headers, serious elements
Slate-700   #374151  Secondary text
Slate-600   #4b5563  Tertiary text
Slate-50    #f8fafc  Subtle backgrounds
Gray-200    #e5e7eb  Borders
Gray-100    #f3f4f6  Light backgrounds
```

### Action Colors (Semantic)

```
Green       #16a34a  Created action
Blue        #2563eb  Updated action
Red         #dc2626  Deleted action
```

### State Colors

```
Blue-50     #eff6ff  User badge background
Blue-700    #1d4ed8  User badge text
Blue-200    #bfdbfe  User badge border

Gray-50     #f9fafb  Hover state
Gray-600    #4b5563  Disabled text
```

---

## 📐 Layout Grid

### Page Layout

```
┌─────────────────────────────────┐
│ Header Section                  │  ← 120px
├─────────────────────────────────┤
│ Filter Section                  │  ← 280px
├─────────────────────────────────┤
│ Table Section                   │  ← Flexible (min 400px)
│                                 │
│                                 │
├─────────────────────────────────┤
│ Pagination Section              │  ← 80px
└─────────────────────────────────┘
```

### Spacing System

```
xs      4px   Padding in compact areas
sm      8px   Standard padding
md      16px  Standard margin
lg      24px  Section spacing
xl      32px  Major section spacing
```

### Table Widths

```
Timestamp:  15% (80px min)
User:       12% (100px min)
Action:     15% (90px min)
Resource:   25% (150px min)
IP:         18% (120px min)
Details:    15% (60px min)
```

---

## 🔤 Typography

### Font Stack

```
Primary: system-ui, -apple-system, sans-serif
Monospace: ui-monospace, "Courier New", monospace
```

### Type Scales

**Headers**

```
Page Title:    text-3xl, font-bold, slate-900
Section Title: text-lg, font-bold, slate-900
Column Header: text-sm, font-bold, gray-600
```

**Body Text**

```
Timestamp: text-xs, font-mono, gray-700 (date)
           text-xs, font-mono, gray-500 (time)
User Name: text-sm, font-normal, blue-700
Action:    text-xs, font-medium, colored
Resource:  text-sm, font-normal, gray-900 (type)
           text-xs, font-normal, gray-500 (ID)
IP:        text-xs, font-mono, gray-600
```

### Line Heights

```
Tight:    1.25 (Headers, labels)
Normal:   1.5  (Body text, table data)
Relaxed:  1.625 (Descriptions)
```

---

## 🏗️ Component Styles

### Badge (Action)

**Created (Green)**

```
Background: rgb(220, 252, 231) [green-50]
Text: rgb(22, 163, 74) [green-700]
Icon: fa-plus-circle
Border: 1px solid rgb(187, 247, 208) [green-200]
Padding: 4px 8px
Border-radius: 6px
Font-size: 12px
Font-weight: 500
```

**Updated (Blue)**

```
Background: rgb(219, 234, 254) [blue-50]
Text: rgb(37, 99, 235) [blue-700]
Icon: fa-edit
Border: 1px solid rgb(191, 219, 254) [blue-200]
Padding: 4px 8px
Border-radius: 6px
```

**Deleted (Red)**

```
Background: rgb(254, 226, 226) [red-50]
Text: rgb(220, 38, 38) [red-700]
Icon: fa-trash
Border: 1px solid rgb(254, 202, 202) [red-200]
Padding: 4px 8px
Border-radius: 6px
```

### Badge (User)

**Registered User**

```
Background: rgb(239, 246, 255) [blue-50]
Text: rgb(29, 78, 216) [blue-700]
Icon: fa-user (left margin)
Border: 1px solid rgb(191, 219, 254) [blue-200]
Padding: 4px 8px
Border-radius: 6px
Font-size: 13px
```

**Unknown User**

```
Background: rgb(243, 244, 246) [gray-100]
Text: rgb(107, 114, 128) [gray-600]
Icon: none
Border: 1px solid rgb(229, 231, 235) [gray-200]
Padding: 4px 8px
Border-radius: 6px
```

### Input Fields

```
Border: 1px solid rgb(229, 231, 235) [gray-200]
Border-radius: 6px
Padding: 8px 12px
Font-size: 14px
Background: white
Focus:
  - Outline: 2px solid rgb(59, 130, 246) [blue-500]
  - Outline-offset: 2px
Placeholder: rgb(107, 114, 128) [gray-600]
```

### Button (Primary - Apply)

```
Background: rgb(37, 99, 235) [blue-700]
Text: white
Padding: 8px 16px
Border-radius: 6px
Font-size: 14px
Font-weight: 500
Cursor: pointer
Hover:
  - Background: rgb(29, 78, 216) [blue-800]
Focus:
  - Outline: 2px solid rgb(59, 130, 246) [blue-500]
```

### Button (Secondary - Reset)

```
Background: rgb(243, 244, 246) [gray-100]
Text: rgb(55, 65, 81) [gray-700]
Padding: 8px 16px
Border-radius: 6px
Border: 1px solid rgb(229, 231, 235) [gray-200]
Font-size: 14px
Font-weight: 500
Cursor: pointer
Hover:
  - Background: rgb(229, 231, 235) [gray-200]
```

### Button (Icon - Details)

```
Background: transparent (normal)
Icon: fa-eye or fa-history
Icon Color: rgb(107, 114, 128) [gray-600]
Padding: 4px 8px
Cursor: pointer
Hover:
  - Background: rgb(243, 244, 246) [gray-100]
  - Icon Color: rgb(75, 85, 99) [gray-700]
  - Border-radius: 4px
```

---

## 📦 Section Layouts

### Header Section

```html
<div class="bg-slate-900 text-white px-6 py-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <i class="fas fa-shield text-2xl"></i>
                <h1 class="text-3xl font-bold">Audit Logs</h1>
            </div>
            <p class="text-slate-300 mt-2">Compliance & security audit trail</p>
        </div>
        <button class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">
            <i class="fas fa-download"></i> Export CSV
        </button>
    </div>
</div>
```

**Specifications**:

- Background: Dark slate (slate-900)
- Padding: 32px (lg)
- Icon size: 24px
- Title: 30px, bold
- Subtitle: 14px, lighter
- Button: Blue background, white text

### Filter Section

```html
<div class="bg-white px-6 py-6 border-b">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Filter Logs</h2>

    <!-- Row 1 -->
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >Start Date</label
            >
            <input type="date" class="w-full" />
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >End Date</label
            >
            <input type="date" class="w-full" />
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >User</label
            >
            <select class="w-full">
                ...
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >Action</label
            >
            <select class="w-full">
                ...
            </select>
        </div>
    </div>

    <!-- Row 2 -->
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >Resource Type</label
            >
            <input type="text" class="w-full" />
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 uppercase"
                >Resource ID</label
            >
            <input type="number" class="w-full" />
        </div>
        <div class="flex gap-2 items-end">
            <button class="flex-1 bg-blue-700 text-white">Apply Filters</button>
            <button class="flex-1 bg-gray-100 text-gray-700">Reset</button>
        </div>
    </div>
</div>
```

**Specifications**:

- Background: White
- Padding: 24px
- Label: 12px, uppercase, bold
- Inputs: 40px height minimum
- Button height: 40px
- Grid gap: 16px

### Table Section

```html
<div class="bg-white">
    <!-- Entry Count -->
    <div class="px-6 py-3 bg-gray-50 text-sm text-gray-600">
        Showing 1 to 25 of 150 entries
    </div>

    <!-- Table -->
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-600">
                    Timestamp
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-600">
                    User
                </th>
                <!-- ... -->
            </tr>
        </thead>
        <tbody>
            <tr class="border-b hover:bg-gray-50">
                <!-- Row content -->
            </tr>
        </tbody>
    </table>
</div>
```

**Specifications**:

- Table width: 100%
- Cell padding: 24px horizontal, 16px vertical
- Border: 1px solid gray-200
- Hover: Subtle gray-50 background
- Header background: White
- Body background: White

---

## 🔍 Detail View Design

### Layout

```
┌─────────────────────────────────┐
│ Back Button                     │
│ Audit Log Entry #123            │
│                                 │
│ Entry Details                   │
├─────────────────────────────────┤
│ User: John Doe                  │
│ Action: Updated                 │
│ Resource: Product #42           │
│ Timestamp: Jan 29, 2026 14:30:45│
│ IP Address: 192.168.1.1         │
│ User Agent: Chrome 120          │
├─────────────────────────────────┤
│ Changes                         │
├─────────────────────────────────┤
│ Field: price                    │
│ Old Value: $99.99               │
│ New Value: $129.99              │
│                                 │
│ Field: stock                    │
│ Old Value: 50                   │
│ New Value: 45                   │
└─────────────────────────────────┘
```

---

## 📱 Responsive Breakpoints

### Desktop (1024px+)

- All filters visible
- Full table width
- 3-column grid for resources
- All details shown

### Tablet (768px-1023px)

- Filters reorganize
- Scrollable table
- Some columns hidden
- Essential info shown

### Mobile (< 768px)

- Stacked filters
- Horizontal scroll table
- Minimized columns
- Essential columns only

---

## ♿ Accessibility

### WCAG 2.1 AA Compliance

**Color Contrast**

```
Text on background: 4.5:1 minimum
Link text: 4.5:1 minimum
UI components: 3:1 minimum
```

**Focus Indicators**

```
All interactive elements have visible focus
Ring: 2px solid blue-500
Outline offset: 2px
```

**Keyboard Navigation**

```
Tab → Move to next element
Shift+Tab → Move to previous element
Enter → Activate button
Escape → Close modal
```

**ARIA Labels**

```
<button aria-label="View audit log details">
<i class="fas fa-eye"></i>
</button>
```

---

## 🎯 Visual Consistency

### Icon System

- **Font**: Font Awesome 6
- **Size**: 16px (default), 20px (large), 12px (small)
- **Color**: Inherits from text color
- **Spacing**: 8px margin from text

### Icon Usage

```
fa-shield       → Page identity
fa-eye          → View details
fa-history      → View history
fa-user         → User identity
fa-plus-circle  → Created action
fa-edit         → Updated action
fa-trash        → Deleted action
fa-network-wired → IP address
fa-download     → Export
```

### Shadow System

```
None (this design uses borders instead)
Keep minimal and serious
```

### Border Radius

```
Buttons: 6px
Badges: 6px
Inputs: 6px
Cards: 4px
```

---

## 🎬 Animations

### Hover Effects

```
Button hover: 100ms opacity/background change
Row hover: 50ms background color change
Input focus: 150ms ring appearance
```

### No Animations (Intentional)

- Page load transitions
- Fade effects
- Slide animations
- Parallax effects

**Reason**: Professional, serious design doesn't need motion

---

## 🌙 Dark Mode (Optional Future)

If implemented:

```
Background: Slate-950 #030712
Text: Slate-100 #f1f5f9
Borders: Slate-700 #374151
Inputs: Slate-900 #0f172a
```

---

## 📋 Checklist for Implementation

- [ ] Header section styled correctly
- [ ] Filter section responsive
- [ ] Table borders and spacing
- [ ] Badge colors accurate
- [ ] Font sizes and weights correct
- [ ] Padding and margins consistent
- [ ] Colors pass WCAG AA contrast
- [ ] Icons aligned and sized
- [ ] Hover states working
- [ ] Focus indicators visible
- [ ] Responsive on mobile
- [ ] Print styles applied
- [ ] No JavaScript errors

---

**Design Version**: 1.0.0
**Last Updated**: January 29, 2026
**Compliance**: WCAG 2.1 AA
