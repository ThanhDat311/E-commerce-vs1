# Admin Design System

## Overview

A comprehensive, reusable design system for all admin dashboard pages in the E-commerce platform. Ensures consistency, maintainability, and accessibility across all administrative interfaces.

**Version**: 1.0.0  
**Updated**: January 29, 2026  
**Status**: Active

---

## 🎨 Design Tokens

### Color Palette

#### Primary Status Colors

```
Critical:     #DC2626 (Red-600)
Warning:      #F97316 (Orange-500)
Low/Success:  #EAB308 (Yellow-500)
Info:         #3B82F6 (Blue-500)
Primary:      #1F2937 (Gray-800)
Secondary:    #6B7280 (Gray-500)
```

#### Background Colors

```
Critical BG:  #FEF2F2 (Red-50)
Warning BG:   #FFF7ED (Orange-50)
Low BG:       #FEFCE8 (Yellow-50)
Info BG:      #EFF6FF (Blue-50)
Light BG:     #F9FAFB (Gray-50)
Dark BG:      #1F2937 (Gray-800)
```

#### Neutral Colors

```
White:        #FFFFFF
Gray-100:     #F3F4F6
Gray-200:     #E5E7EB
Gray-300:     #D1D5DB
Gray-500:     #6B7280
Gray-700:     #374151
Gray-800:     #1F2937
Gray-900:     #111827
```

#### Gradient Definitions

```
Orange-to-Red:   from-orange-600 to-red-600
Blue-to-Purple:  from-blue-600 to-purple-600
Green-to-Teal:   from-green-600 to-teal-600
Red-to-Pink:     from-red-600 to-pink-600
```

### Typography

#### Font Stack

```
Font Family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif
Fallback:    system-ui, sans-serif
```

#### Size Scale

```
xs:  0.75rem  (12px)  — Captions, badges
sm:  0.875rem (14px)  — Labels, secondary text
base: 1rem    (16px)  — Body text
lg:  1.125rem (18px)  — Subheadings
xl:  1.25rem  (20px)  — Card titles
2xl: 1.5rem   (24px)  — Section headers
3xl: 1.875rem (30px)  — Page titles
4xl: 2.25rem  (36px)  — Hero titles
```

#### Weight Scale

```
Regular:   400  — Body text, descriptions
Medium:    500  — Labels, badges
Semibold:  600  — Card titles, emphasis
Bold:      700  — Page titles, strong emphasis
```

#### Line Height Scale

```
tight:     1.25   — Small text, badges
snug:      1.375  — Labels
normal:    1.5    — Body text
relaxed:   1.625  — Lists
loose:     2      — Headings
```

### Spacing Scale

```
0:    0px
1:    0.25rem  (4px)
2:    0.5rem   (8px)
3:    0.75rem  (12px)
4:    1rem     (16px)
6:    1.5rem   (24px)
8:    2rem     (32px)
12:   3rem     (48px)
16:   4rem     (64px)
```

### Border Radius

```
none:     0px
sm:       0.375rem  (6px)
base:     0.5rem    (8px)
lg:       0.75rem   (12px)
xl:       1rem      (16px)
full:     9999px    (pill shape)
```

### Shadows

```
sm:     0 1px 2px 0 rgba(0, 0, 0, 0.05)
base:   0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)
md:     0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)
lg:     0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)
xl:     0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)
```

---

## 🔘 Button System

### Button Types

#### Primary Button

**Usage**: Main actions, form submission  
**Color**: Blue  
**Hover**: Darker blue

```blade
<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Save Changes
</button>
```

**Variants**:

- **Large**: `px-6 py-3 text-lg`
- **Small**: `px-3 py-1 text-sm`
- **Icon**: `flex items-center gap-2`
- **Loading**: Add `opacity-75 cursor-not-allowed` disabled state

#### Secondary Button

**Usage**: Alternative actions, cancellations  
**Color**: Gray  
**Hover**: Darker gray

```blade
<button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Cancel
</button>
```

#### Danger Button

**Usage**: Destructive actions (delete, remove)  
**Color**: Red  
**Hover**: Darker red

```blade
<button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Delete
</button>
```

#### Success Button

**Usage**: Positive confirmations  
**Color**: Green  
**Hover**: Darker green

```blade
<button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Confirm
</button>
```

#### Warning Button

**Usage**: Warnings, cautions  
**Color**: Orange  
**Hover**: Darker orange

```blade
<button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Schedule
</button>
```

#### Ghost Button

**Usage**: Secondary actions, links  
**Color**: Transparent with border  
**Hover**: Light background

```blade
<button class="bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
    Learn More
</button>
```

### Button States

#### Default (Enabled)

```
Background:  Full opacity
Cursor:      pointer
Transition:  All 0.2s ease
```

#### Hover

```
Background:  Darker shade (-100 in color scale)
Shadow:      Add md shadow
Transform:   translateY(-1px) if desired
```

#### Active/Pressed

```
Background:  Darker shade (-200 in color scale)
Shadow:      Inner shadow effect
```

#### Disabled

```
Opacity:     50%
Cursor:      not-allowed
Pointer:     None
```

#### Loading

```
Opacity:     75%
Cursor:      not-allowed
Icon:        Spinner animation
```

### Button Groups

```blade
<div class="flex gap-2">
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
        Save
    </button>
    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
        Cancel
    </button>
</div>
```

---

## 🏷️ Badge System

### Badge Types

#### Status Badge (Critical)

**Color**: Red  
**Usage**: Critical alerts, errors

```blade
<span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1">
    <i class="fas fa-exclamation-circle"></i>
    CRITICAL
</span>
```

#### Status Badge (Warning)

**Color**: Orange  
**Usage**: Warnings, cautions

```blade
<span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1">
    <i class="fas fa-exclamation"></i>
    WARNING
</span>
```

#### Status Badge (Success)

**Color**: Green  
**Usage**: Success, completion

```blade
<span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1">
    <i class="fas fa-check-circle"></i>
    COMPLETED
</span>
```

#### Status Badge (Info)

**Color**: Blue  
**Usage**: Information, neutral

```blade
<span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1">
    <i class="fas fa-info-circle"></i>
    INFO
</span>
```

#### Info Badge (Light)

**Color**: Gray background, dark text  
**Usage**: Secondary information

```blade
<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
    New
</span>
```

### Badge Variants

#### Pill Shape

```
border-radius: 9999px (rounded-full)
padding:       0.25rem 0.75rem (small) to 0.5rem 1rem (large)
```

#### Outline Badge

```blade
<span class="border-2 border-red-600 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
    ALERT
</span>
```

#### Animated Badge (Pulsing)

```blade
<span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold animate-pulse">
    CRITICAL
</span>
```

---

## 🎴 Card System

### Basic Card

```blade
<div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Card Title</h3>
    <p class="text-gray-600">Card content goes here</p>
</div>
```

**Structure**:

- Background: White
- Border: 1px gray-200
- Shadow: md
- Padding: 1.5rem (6)
- Border-radius: 0.5rem (lg)

### Status Card (Colored)

```blade
<div class="bg-red-50 rounded-lg shadow-md p-6 border-l-4 border-red-600">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-gray-700 text-sm font-semibold uppercase">Critical Items</p>
            <p class="text-3xl font-bold text-red-700 mt-2">{{ $count }}</p>
        </div>
        <i class="fas fa-exclamation-circle text-red-600 text-2xl opacity-20"></i>
    </div>
    <div class="text-red-600 text-xs font-semibold mt-2">
        <i class="fas fa-arrow-up"></i> Immediate action needed
    </div>
</div>
```

**Variants**:

- **Critical**: `bg-red-50`, `border-red-600`, `text-red-700`
- **Warning**: `bg-orange-50`, `border-orange-500`, `text-orange-600`
- **Success**: `bg-green-50`, `border-green-600`, `text-green-700`
- **Info**: `bg-blue-50`, `border-blue-500`, `text-blue-600`

### Stat Card

```blade
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">$45,231.89</p>
            <p class="text-green-600 text-sm font-medium mt-1">
                <i class="fas fa-arrow-up"></i> +12.5% from last month
            </p>
        </div>
        <div class="bg-blue-50 rounded-lg p-3">
            <i class="fas fa-dollar-sign text-blue-600 text-2xl"></i>
        </div>
    </div>
</div>
```

### Card Grid

**2-Column Grid**:

```blade
<div class="grid grid-cols-2 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-md p-6">...</div>
    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-md p-6">...</div>
</div>
```

**3-Column Grid**:

```blade
<div class="grid grid-cols-3 gap-6">
    <!-- Cards -->
</div>
```

**4-Column Grid** (Most common for dashboards):

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Cards -->
</div>
```

**Responsive Card Grid**:

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Summary Cards -->
</div>
```

---

## 📊 Table System

### Basic Table

```blade
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                <th class="px-6 py-3 text-left text-sm font-semibold">Column 1</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Column 2</th>
                <th class="px-6 py-3 text-right text-sm font-semibold">Column 3</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-sm text-gray-900">Data 1</td>
                <td class="px-6 py-4 text-sm text-gray-600">Data 2</td>
                <td class="px-6 py-4 text-sm text-right text-gray-900">Data 3</td>
            </tr>
        </tbody>
    </table>
</div>
```

### Table with Status Rows

```blade
<!-- Critical Row (Red) -->
<tr class="bg-red-50 border-b border-red-200 hover:bg-red-100 border-l-4 border-red-600 transition">
    <td class="px-6 py-4"><span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold animate-pulse">CRITICAL</span></td>
    <td class="px-6 py-4 text-sm text-gray-900">Product Name</td>
    <td class="px-6 py-4 text-sm text-gray-600">Details</td>
</tr>

<!-- Warning Row (Orange) -->
<tr class="bg-orange-50 border-b border-orange-200 hover:bg-orange-100 border-l-4 border-orange-500 transition">
    <td class="px-6 py-4"><span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">WARNING</span></td>
    <td class="px-6 py-4 text-sm text-gray-900">Product Name</td>
    <td class="px-6 py-4 text-sm text-gray-600">Details</td>
</tr>

<!-- Success Row (Green) -->
<tr class="bg-green-50 border-b border-green-200 hover:bg-green-100 border-l-4 border-green-600 transition">
    <td class="px-6 py-4"><span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold">COMPLETED</span></td>
    <td class="px-6 py-4 text-sm text-gray-900">Product Name</td>
    <td class="px-6 py-4 text-sm text-gray-600">Details</td>
</tr>
```

### Table with Progress Bar

```blade
<tr class="border-b border-gray-200 hover:bg-gray-50">
    <td class="px-6 py-4 text-sm text-gray-900">Stock Level</td>
    <td class="px-6 py-4">
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-red-600 h-2 rounded-full" style="width: 24%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-1">24% of minimum</p>
    </td>
</tr>
```

### Responsive Table

```blade
<div class="overflow-x-auto">
    <table class="w-full">
        <!-- Table content -->
    </table>
</div>
```

---

## 🎨 Layout Components

### Header Section

```blade
<div class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-8 shadow-lg">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-icon text-3xl text-yellow-300"></i>
                <div>
                    <h1 class="text-4xl font-bold">Page Title</h1>
                    <p class="text-orange-100 mt-1">Subtitle or description</p>
                </div>
            </div>
            <div class="flex gap-2">
                <!-- Action buttons -->
            </div>
        </div>
    </div>
</div>
```

**Header Variants**:

- **Orange-Red Gradient**: Analytics pages
- **Blue-Purple Gradient**: Settings pages
- **Green-Teal Gradient**: Success pages
- **Solid Color**: Quick access pages

### Filter Section

```blade
<div class="bg-orange-500 bg-opacity-20 rounded-lg p-4 backdrop-blur border border-orange-400 border-opacity-30">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <select class="bg-white text-gray-900 px-3 py-2 rounded border border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
            <option>Filter Option</option>
        </select>
        <!-- More filters -->
        <button class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded font-semibold">
            Apply
        </button>
    </div>
</div>
```

### Info Box

```blade
<div class="bg-red-50 rounded-lg p-4 border border-red-200">
    <div class="flex gap-3">
        <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
        <div>
            <h4 class="text-red-900 font-semibold">Important Notice</h4>
            <p class="text-red-700 text-sm mt-1">Message content goes here</p>
        </div>
    </div>
</div>
```

**Variants**:

- **Red/Critical**: `bg-red-50`, `border-red-200`, `text-red-*`
- **Orange/Warning**: `bg-orange-50`, `border-orange-200`, `text-orange-*`
- **Green/Success**: `bg-green-50`, `border-green-200`, `text-green-*`
- **Blue/Info**: `bg-blue-50`, `border-blue-200`, `text-blue-*`

---

## 🔐 Form Elements

### Input Field

```blade
<div class="mb-4">
    <label class="text-sm font-medium text-gray-700 block mb-2">Label</label>
    <input type="text" placeholder="Placeholder" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>
```

### Select Dropdown

```blade
<div class="mb-4">
    <label class="text-sm font-medium text-gray-700 block mb-2">Select Option</label>
    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option>Choose one...</option>
        <option>Option 1</option>
        <option>Option 2</option>
    </select>
</div>
```

### Text Area

```blade
<div class="mb-4">
    <label class="text-sm font-medium text-gray-700 block mb-2">Message</label>
    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
</div>
```

---

## 📈 Data Visualization

### Progress Bar

```blade
<div class="w-full bg-gray-200 rounded-full h-2">
    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
</div>
<p class="text-xs text-gray-500 mt-1">75%</p>
```

**Color Variants**:

- **Critical**: `bg-red-600`
- **Warning**: `bg-orange-500`
- **Success**: `bg-green-600`
- **Info**: `bg-blue-600`

### Mini Gauge

```blade
<div class="flex items-center gap-2">
    <span class="text-lg font-bold text-gray-900">85%</span>
    <div class="flex gap-1">
        <div class="w-1 h-4 bg-green-600 rounded"></div>
        <div class="w-1 h-4 bg-green-600 rounded"></div>
        <div class="w-1 h-4 bg-green-600 rounded"></div>
        <div class="w-1 h-4 bg-gray-300 rounded"></div>
    </div>
</div>
```

---

## 🎯 Responsive Design

### Breakpoints

```
Mobile:   < 640px    (1 column layouts)
Tablet:   640-1024px (2 column layouts)
Desktop:  > 1024px   (3-4 column layouts)
```

### Responsive Utilities

```
Mobile-first:
- `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4`
- `text-base md:text-lg lg:text-xl`
- `px-4 md:px-6 lg:px-8`

Hide/Show:
- `hidden md:block` (hide on mobile, show on tablet+)
- `block md:hidden` (show on mobile, hide on tablet+)
```

---

## ♿ Accessibility Standards

### WCAG 2.1 AA Compliance

- **Color Contrast**: Minimum 4.5:1 for text
- **Focus States**: Visible on all interactive elements
- **Semantic HTML**: Proper heading hierarchy
- **Icon + Text**: Never icon-only for meaning
- **Motion**: No flashing > 3 per second
- **Alternative Text**: All images have alt text

### Focus States

```blade
<!-- Button with visible focus -->
<button class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    Button
</button>

<!-- Input with visible focus -->
<input class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
```

---

## 🔌 Tailwind Configuration

### Custom Config (tailwind.config.js)

```javascript
module.exports = {
    theme: {
        extend: {
            colors: {
                critical: "#DC2626",
                warning: "#F97316",
                success: "#10B981",
            },
            spacing: {
                sidebar: "16rem",
                header: "4rem",
            },
        },
    },
};
```

### Custom CSS Variables

```css
:root {
    --color-critical: #dc2626;
    --color-warning: #f97316;
    --color-success: #10b981;
    --color-info: #3b82f6;

    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
}
```

---

## 📝 Component Usage Examples

### Common Pattern: Card with Header

```blade
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
        <h2 class="text-xl font-semibold">Section Title</h2>
    </div>
    <div class="p-6">
        <p class="text-gray-600">Content goes here</p>
    </div>
</div>
```

### Common Pattern: Action Card with Buttons

```blade
<div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Action Card</h3>
    <p class="text-gray-600 mb-6">Description of action</p>
    <div class="flex gap-2">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
            Primary Action
        </button>
        <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
            Secondary Action
        </button>
    </div>
</div>
```

### Common Pattern: Stat Row

```blade
<div class="flex items-center justify-between p-4 border-b border-gray-200">
    <div>
        <p class="text-sm text-gray-600">Label</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">1,234</p>
    </div>
    <div class="text-right">
        <p class="text-green-600 font-semibold">↑ 12.5%</p>
        <p class="text-xs text-gray-500">vs. last month</p>
    </div>
</div>
```

---

## 🎭 Component Usage Checklist

- [ ] All buttons use system colors (primary/secondary/danger/warning/success)
- [ ] All badges have icons paired with text
- [ ] All cards have consistent shadows and borders
- [ ] All tables use striped rows or hover states
- [ ] Status colors match across all pages
- [ ] Typography hierarchy is consistent
- [ ] Spacing uses the defined scale (4, 8, 12, 16, 24, 32, 48, 64)
- [ ] Focus states visible on all interactive elements
- [ ] Mobile responsive at 640px and 1024px breakpoints
- [ ] Gradients use defined combinations
- [ ] Icons properly sized and colored
- [ ] Forms have proper labels and focus states
- [ ] Info boxes use the color system
- [ ] All pages use the same admin layout
- [ ] No inline styles (use Tailwind classes)
- [ ] No duplicate styling across pages

---

## 🚀 Implementation Guide

### Adding New Admin Page

1. **Create the view**: `resources/views/admin/{name}/index.blade.php`
2. **Extend admin layout**: `@extends('admin.layout.admin')`
3. **Use design system colors**: Reference this guide
4. **Use reusable components**: Check `/resources/views/components/admin/`
5. **Follow responsive pattern**: `grid-cols-1 md:grid-cols-2 lg:grid-cols-4`
6. **Add status cards** if needed: Use the card template
7. **Add data table** if needed: Use the table template
8. **Test accessibility**: Check WCAG compliance
9. **Test responsiveness**: Check at 375px, 768px, 1440px widths

### Modifying Existing Style

1. Check this guide for the component type
2. Update the relevant template
3. Test on all breakpoints
4. Update documentation if changing tokens

---

## 📞 Support & Updates

This design system should be referenced for all new admin pages. For questions:

1. Check this document first
2. Review existing admin pages using the same patterns
3. Check the specific feature documentation (Low Stock Alerts, Revenue Analytics, etc.)
4. Ask development team for clarification

---

**Version**: 1.0.0  
**Last Updated**: January 29, 2026  
**Maintained By**: Development Team  
**Status**: Active & Complete
