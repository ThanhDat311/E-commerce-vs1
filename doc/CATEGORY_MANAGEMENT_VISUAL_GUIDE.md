# Category Management - Visual Layout Guide

## Dashboard Layout Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                     ADMIN HEADER (Navbar)                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  CATEGORY MANAGEMENT                                [+ Add New...]   │
│  Organize and manage product categories...                           │
│                                                                       │
├────────────────────────────────────┬─────────────────────────────────┤
│                                    │                                  │
│  CATEGORY TREE (LEFT SIDEBAR)      │  CATEGORY DETAILS (MAIN PANEL)  │
│  ─────────────────────────────────  │  ──────────────────────────────│
│                                    │                                  │
│  [Search box]                      │  📋 Basic Information           │
│                                    │  ├─ Category Name *             │
│  > Electronics        [12]         │  ├─ Slug                       │
│    > Mobile          [28]          │  ├─ Parent Category            │
│    > Laptops         [15]          │  └─ Description                │
│  > Clothing          [45]          │                                  │
│  > Home & Garden     [8]           │  📸 Category Thumbnail         │
│  > Sports           [22]           │  ├─ [Drag-drop area]           │
│                                    │  └─ [Image preview]            │
│                                    │                                  │
│                                    │  ⚙️ Status                      │
│                                    │  ├─ [Toggle] Active            │
│                                    │                                  │
│                                    │  📊 Statistics                  │
│                                    │  ├─ Total Products: 28         │
│                                    │  ├─ Created: Jan 15, 2026      │
│                                    │  └─ Updated: Today             │
│                                    │                                  │
│                                    │  [Save Changes] [Cancel]        │
│                                    │  [Delete Category]              │
│                                    │                                  │
└────────────────────────────────────┴─────────────────────────────────┘
```

## Create/Edit Form Layout

```
┌─────────────────────────────────────────────────────────────────┐
│  CREATE / EDIT CATEGORY              [← Back]                  │
│  Update category details and configuration.                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────┬───────────────────────┐   │
│  │ BASIC INFORMATION (2/3 width)   │ STATUS (1/3 width)   │   │
│  │                                  │                        │   │
│  │ Category Name *                  │ ⚙️ STATUS             │   │
│  │ [________________]               │ ☑️ Active             │   │
│  │                                  │ Visible in store      │   │
│  │ Slug     │  Parent Category     │                        │   │
│  │ [_____] │ [_____________]      │ 📊 STATISTICS          │   │
│  │ auto-generated                   │ Products: 28          │   │
│  │                                  │ Created: ...           │   │
│  │ Description                      │ Updated: ...           │   │
│  │ [_________________]              │                        │   │
│  │                                  │ 🔘 ACTIONS             │   │
│  │ 📸 THUMBNAIL                    │ [Save Changes]         │   │
│  │ [   Drag-drop zone    ]         │ [Cancel]               │   │
│  │ Click to browse                  │ [Delete]               │   │
│  │ PNG, JPG up to 5MB               │                        │   │
│  │ [Preview if uploaded]            │                        │   │
│  │                                  │                        │   │
│  └─────────────────────────────────┴───────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

## Color Scheme

```
Primary Colors:
├─ Indigo-600 (#4F46E5) ────── Primary actions (buttons, links)
├─ Indigo-700 (#4338CA) ────── Hover states
├─ Indigo-50  (#EEF2FF) ────── Backgrounds, highlights
└─ Indigo-100 (#E0E7FF) ────── Badges

Neutral Colors:
├─ White      (#FFFFFF) ────── Card backgrounds
├─ Gray-50    (#F9FAFB) ────── Input backgrounds
├─ Gray-100   (#F3F4F6) ────── Hover backgrounds
├─ Gray-300   (#D1D5DB) ────── Border color
├─ Gray-600   (#4B5563) ────── Secondary text
└─ Gray-900   (#111827) ────── Primary text

Status Colors:
├─ Green-50   (#F0FDF4) ────── Success alerts
├─ Green-600  (#16A34A) ────── Success text
├─ Red-50     (#FEF2F2) ────── Error alerts
└─ Red-600    (#DC2626) ────── Error/Delete

Badges:
├─ Background: Indigo-100 (#E0E7FF)
└─ Text: Indigo-700 (#4338CA)
```

## Category Tree Item States

```
Normal State:
├─ Category Name [12]
└─ Expand arrow (if children)

Hover State:
├─ Background: Gray-100
├─ Category Name [12]
├─ Edit button ✏️
└─ Expand arrow

Expanded State:
├─ ▼ Electronics [12]
│  ├─ Mobile [28]
│  ├─ Laptops [15]
│  └─ [Border line showing nesting]
└─ [Bold/Active text if selected]

Collapsed State:
├─ ▶ Electronics [12]
├─ [Children hidden]
└─ [Border line stops]
```

## Responsive Breakpoints

```
Mobile (< 768px):
┌──────────────────┐
│ CATEGORY TREE    │  <- Single column, full width
├──────────────────┤
│ FORM PANEL       │  <- Stacked below
│                  │
└──────────────────┘

Tablet (768px - 1024px):
┌────────────┬──────────────┐
│ TREE (30%) │ FORM (70%)   │  <- 2 columns
│            │              │
└────────────┴──────────────┘

Desktop (> 1024px):
┌─────┬──────────────┬─────┐
│ TRE │ FORM CONTENT │ ACT │  <- 3 columns (Tree | Form | Sidebar)
│  E  │              │ ION │     Tree: sticky, Search: sticky
│     │              │ S   │
└─────┴──────────────┴─────┘
```

## Interactive Elements

### Buttons

```
Primary (Save, Create):
┌──────────────────┐
│  ▼ SAVE CHANGES  │  Blue bg, white text, hover darkens
└──────────────────┘

Secondary (Cancel):
┌──────────────────┐
│ CANCEL           │  Gray bg, dark text, hover darker
└──────────────────┘

Danger (Delete):
┌──────────────────┐
│ 🗑️ DELETE        │  Red bg, white text, hover darkens
└──────────────────┘

Ghost (Back):
┌──────────────────┐
│ ← Back           │  No fill, blue text, hover subtly
└──────────────────┘
```

### Form Elements

```
Input Fields:
├─ Border: Gray-300 (1px)
├─ Background: White
├─ Focus: Blue ring (2px), Blue border
├─ Error: Red border (2px)
└─ Placeholder: Gray-500

Dropdowns:
├─ Same as inputs
├─ Cursor: pointer
├─ Arrow: Right-pointing
└─ Options: Scrollable on overflow

Textareas:
├─ Same as inputs
├─ Resize: none (fixed height)
├─ Rows: 4 (adjustable)
└─ Placeholder text

Toggle Switch:
├─ Size: w-4 h-4
├─ Color: Indigo when checked
├─ Cursor: pointer
└─ Label: to the right
```

### Cards

```
Standard Card:
┌─────────────────────────┐
│ 📦 CARD TITLE           │  ← Header (font-bold, text-lg)
├─────────────────────────┤
│ Card content here       │  ← Body (p-6, spacing)
│ With proper spacing     │
└─────────────────────────┘

Properties:
├─ Background: White
├─ Border: 1px Gray-100
├─ Border Radius: 12px
├─ Shadow: md (subtle)
├─ Padding: 24px
└─ Gap: 24px between cards

Alert Card:
┌─ Success: Green-50 bg, Green-600 text, Green-200 border
├─ Error: Red-50 bg, Red-600 text, Red-200 border
├─ Info: Blue-50 bg, Blue-600 text, Blue-200 border
└─ Icon + Message + Dismiss button
```

## Category Tree Example

```
📂 All Categories                          SEARCH: "abc"
━━━━━━━━━━━━━━━━━━━━━━━━━━━

▼ Electronics                      [142]  ✏️
   ▼ Mobile Devices               [28]   ✏️
      • Apple iPhones             [12]   ✏️
      • Samsung Phones            [8]    ✏️
      • Other Brands              [8]    ✏️
   ▼ Laptops & Computers          [45]   ✏️
      • Gaming Laptops            [15]   ✏️
      • Ultrabooks                [20]   ✏️
      • Desktops                  [10]   ✏️
   ▶ Accessories                  [69]   ✏️

▼ Clothing                         [234]  ✏️
   ▶ Men's Fashion                [89]   ✏️
   ▶ Women's Fashion              [112]  ✏️
   ▶ Accessories                  [33]   ✏️

▼ Home & Garden                    [78]   ✏️
   ▶ Furniture                    [45]   ✏️
   ▶ Decor                        [33]   ✏️

▶ Sports & Outdoors                [156]  ✏️

Numbers in [brackets] = product count per category
```

## Data Validation Flow

```
User Input
    ↓
[Frontend Validation] ← HTML5 required, pattern, max
    ↓
[Form Submission]
    ↓
[Server Validation] ← Laravel rules
    ↓
├─ Valid?
│  ├─ Yes → Save to DB → Redirect + Success Alert
│  └─ No → Show form with error messages
│
[File Upload Path]
    ↓
[File Validation] ← Type, size, mime
    ↓
├─ Valid?
│  ├─ Yes → Generate filename → Store → Save path
│  └─ No → Show error message
│
[Unique Constraint]
    ↓
├─ Database Unique Index on (name, slug)
├─ Constraint error → Display validation message
└─ Success → Form submitted
```

## Accessibility Features

```
✓ Semantic HTML (form, label, fieldset)
✓ ARIA labels on all inputs
✓ Color not sole indicator (icons + text)
✓ Keyboard navigation (Tab, Enter, Space)
✓ Focus states clearly visible (ring-2)
✓ Error messages associated with inputs
✓ Sufficient color contrast ratios
✓ SVG icons with alt text
✓ Button states distinct
✓ Modal-like dialogs have focus management
```

---

**Layout Version**: 1.0  
**Last Updated**: January 28, 2026  
**Status**: ✅ Ready for Implementation
